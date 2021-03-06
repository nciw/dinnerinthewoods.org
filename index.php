<?php

use Bramus\Router\Router;
use Postmark\PostmarkClient;
use RedBeanPHP\R;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/functions.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/src');
$dotenv->load();
$dotenv->required([
    'EVENT_TICKET_PRICE_1',
    'EVENT_TICKET_PRICE_2',
    'EVENT_TICKET_PRICE_3',
    'ENHANCER_TICKET_PRICE',
    'CABANA_PRICE',
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASS',
    'POSTMARK_API_KEY',
    'STRIPE_API_SECRET_KEY',
    'STRIPE_API_PUBLIC_KEY',
]);

$_SERVER['receipt-url'] = $_SERVER['HTTP_HOST'] . "/thank-you/";
$_SERVER['manage-url'] = $_SERVER['HTTP_HOST'] . "/manage/";
$_SERVER['manage-guest-url'] = $_SERVER['HTTP_HOST'] . "/guest/";

$router = new Router();
$r = R::setup('mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'], $_SERVER['DB_USER'],
    $_SERVER['DB_PASS']);
R::freeze(true);
// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

// Get event pricing based on dates
list($_SERVER['EVENT_TICKET_PRICE'], $_SERVER['TABLE_TICKET_PRICE']) = getEventPricing(new DateTime('now',
    new DateTimeZone('America/Chicago')));


$router->before('GET|POST', '/admin/.*', function () {
    session_start();
    if (!isset($_SESSION['user'])) {
        header('location: /auth/login');
        exit();
    }
});

$router->get('/auth/login', function () {
    include 'views/common/head.php';
    include 'views/admin-login.php';
    include 'views/common/footer.php';
});

$router->post('/auth/login', function () {
    if (($_POST['username'] == $_SERVER['ADMIN_USER']) && ($_POST['password'] == $_SERVER['ADMIN_PASS'])) {
        session_start();
        $_SESSION['user'] = $_POST['username'];
        header('Location: /admin/orders');
    } else {
        header('Location: /auth/login?alert=error');
    }
});

// Static route: / (homepage)
$router->get('/', function () {
    checkIfTicketsAreOnSale();
    include 'views/common/head.php';
    include 'views/family.php';
    include 'views/common/footer.php';
});

$router->get('/notify', function () {
    include 'views/common/head.php';
    include 'views/notify.php';
    include 'views/common/footer.php';
});

$router->get('/admin/orders', function () {
    $orders = R::findAll('orders');

    include 'views/common/head.php';
    include 'views/admin.php';
    include 'views/common/footer.php';
});

$router->get('/admin/send-survey', function () {
    $guests = R::findAll('guests', ' order by name asc ');
    $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);

    foreach ($guests as $guest) {
        try {
            $client->sendEmailWithTemplate(
                $_SERVER['POSTMARK_FROM'],
                $guest->email,
                $_SERVER['POSTMARK_SURVEY_TEMPLATE'],
                [
                    'name' => $guest->name,
                    'product_name' => 'Dinner in the Woods ' . date('Y'),
                ],
                true, //inline css
                null, //tag
                true, //track opens
                null, //reply to
                null //cc
            );
            echo 'Sent email to ' . $guest->email . '<br/>';
        } catch (Exception $e) {
            echo 'FAILED to send email to ' . $guest->email . '<br/>';
        }
    }
});

$router->get('/admin/guests/export', function () {
    R::csv('SELECT * FROM guests', [], [
        'id',
        'order number',
        'name',
        'email',
        'phone',
        'childcare? (0=no, 1=yes)',
        'valet? (0=no, 1=yes)',
        'food restrictions? (0=no, 1=vegetarian, 2=vegan, 3=gluten)',
        'table',
        'paddle',
        'stripe_id',
        'uuid',
        'date created'
    ], 'guests.csv', true);
    exit;
});

$router->get('/admin/orders/export', function () {
    R::csv('SELECT * FROM orders', [], [
        'order number',
        'ticket quantity',
        'enhancer quantity',
        'ticket cents',
        'enhancer cents',
        'additional cents',
        'cabana cents',
        'total cents',
        'first name',
        'last name',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'payment type (0=credit, 1=check)',
        'stripe token',
        'date created',
        'uuid'
    ], 'orders.csv', true);
    exit;
});

$router->get('/admin/guest/list', function () {
    $guests = R::findAll('guests', ' order by name asc ');

    include 'views/common/head.php';
    include 'views/admin-guest-list.php';
    include 'views/common/footer.php';
});

$router->get('/admin/guest/add-card/{id}', function ($id) {
    $guest = R::load('guests', $id);

    include 'views/common/head.php';
    include 'views/admin-guest-add-card.php';
    include 'views/common/footer.php';
});

$router->get('/admin/guest/delete-card/{id}', function ($id) {
    $guest = R::load('guests', $id);

    Stripe::setApiKey($_SERVER['STRIPE_API_SECRET_KEY']);
    /** @var \Stripe\Customer $customer */
    $customer = Customer::retrieve($guest->stripe_id);
    $customer->delete();

    $guest->stripe_id = null;
    R::store($guest);

    header('Location: /admin/guest/add-card/' . $guest->id . '?alert=success&msg=Credit card deleted '.$guest->name);

});

$router->get('/admin/guest/checkout/{id}', function ($id) {
    $guest = R::load('guests', $id);

    include 'views/common/head.php';
    include 'views/admin-guest-checkout.php';
    include 'views/common/footer.php';
});

$router->post('/admin/guest/checkout/{id}', function ($id) {
    $guest = R::load('guests', $id);
    $totalChargeCents = convertPossibleFloatToCents($_POST['totalCharge']);
    $guest->checkout_cents = $totalChargeCents;
    Stripe::setApiKey($_SERVER['STRIPE_API_SECRET_KEY']);

    if (isset($_POST['sendViaStripe']) && $_POST['sendViaStripe'] == 'on') {
        if (empty($guest->stripe_id)) {
            $customer = Customer::create([
                'description' => $guest->name . ' - ' . $guest->email,
                'email' => $guest->email,
            ]);
        } else {
            $customer = $guest->stripe_id;
        }

        $invoiceItem = \Stripe\InvoiceItem::create([
            'customer' => $customer,
            'amount' => $guest->checkout_cents,
            'currency' => 'usd',
            'description' => 'Dinner in the Woods ' . date('Y'),
        ]);

        /** @var $invoice \Stripe\Invoice*/
        $invoice = \Stripe\Invoice::create([
            'customer' => $customer,
            'billing' => 'send_invoice',
            'days_until_due' => 1,
            'description' => 'Dinner in the Woods ' . date('Y'),
        ]);
        $invoice->finalizeInvoice();

        $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);
        $client->sendEmailWithTemplate(
            $_SERVER['POSTMARK_FROM'],
            $guest->email,
            $_SERVER['POSTMARK_TEMPLATE_STRIPE_INVOICE'],
            [
                'name' => $guest->name,
                'product_name' => 'Dinner in the Woods ' . date('Y'),
                'action_receipt_url' => $invoice->hosted_invoice_url,
            ],
            true, //inline css
            null, //tag
            true, //track opens
            null, //reply to
            null //cc
        );

        R::store($guest);
        header('Location: /admin/guest/list/?action=success&msg=Invoice created for ' . $guest->name);
    } else {
        if (!empty($guest->stripe_id)) {
            $charge = \Stripe\Charge::create([
                'amount' => $guest->checkout_cents,
                'currency' => 'usd',
                'customer' => $guest->stripe_id,
            ]);
            $guest->checkout_stripe_id = $charge->id;
        }

        $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);
        $client->sendEmailWithTemplate(
            $_SERVER['POSTMARK_FROM'],
            $guest->email,
            $_SERVER['POSTMARK_TEMPLATE_STRIPE_RECEIPT'],
            [
                'name' => $guest->name,
                'total' => $guest->checkout_cents/100,
                'product_name' => 'Dinner in the Woods ' . date('Y'),
            ],
            true, //inline css
            null, //tag
            true, //track opens
            null, //reply to
            null //cc
        );

        R::store($guest);

        header('Location: /admin/guest/list/?action=success&msg=Charge created for ' . $guest->name);

    }

});

$router->post('/admin/guest/add-card/{id}', function ($id) {
    $guest = R::load('guests', $_POST['guestId']);
    $guest->name = $_POST['name'];
    $guest->email = $_POST['email'];

    Stripe::setApiKey($_SERVER['STRIPE_API_SECRET_KEY']);
    $customer = Customer::create([
        'description' => $_POST['name'] . ' - ' . $_POST['email'],
        'source' => $_POST['stripeToken'], // obtained with Stripe.js
        'email' => $_POST['email'],
    ]);
    $guest->stripe_id = $customer->id;
    R::store($guest);

    header('Location: /admin/guest/list?alert=success&msg=Added credit card for '.$guest->name);
});

$router->post('/admin/guest/list', function () {
    $ticketEnhancerQty = getInteger($_POST['enhancerQty']);
    $guestId = getInteger($_POST['guestId']);

    $guest = R::load('guests', $guestId);
    $guest->enhancer_qty = $guest->enhancer_qty + $ticketEnhancerQty;
    R::store($guest);

    header('Location: /admin/guest/list?alert=success&msg=Added '. $ticketEnhancerQty . ' for ' . $guest->name);
});

$router->get('/admin/order/{id}', function ($id) {
    $order = R::load('orders', $id);
    $guests = R::findAll('guests', ' order_id = ?', [$order->id]);

    include 'views/common/head.php';
    include 'views/admin-order-details.php';
    include 'views/common/footer.php';
});

$router->get('/admin/reminder-email', function () {
    $orders = R::findAll('orders');
    $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);

    foreach ($orders as $order) {
        $client->sendEmailWithTemplate(
            $_SERVER['POSTMARK_FROM'],
            $order->email,
            $_SERVER['POSTMARK_REMINDER_TEMPLATE'],
            [
                'name' => $order->first_name,
                'product_name' => 'Dinner in the Woods ' . date('Y'),
                'action_manage_guests_url' => 'https://' . $_SERVER['manage-url'] . $order->uuid,
            ],
            true, //inline css
            null, //tag
            true, //track opens
            null, //reply to
            null //cc
        );
    }

    include 'views/common/head.php';
    include 'views/admin-order-details.php';
    include 'views/common/footer.php';
});

$router->post('/admin/order/{id}', function ($id) {
    $order = R::load('orders', $id);
    $parametersToSearch = $_POST['guestsArray'];
    array_push($parametersToSearch, $order->id);
    $guests = R::findAll('guests', ' id IN(' . R::genSlots($_POST['guestsArray']) . ') AND order_id = ?',
        $parametersToSearch);

    foreach ($guests as $id => $guest) {
        $guest->table = (int)$_POST['table'][$id];
        $guest->paddle = (int)$_POST['paddle'][$id];
        R::store($guest);
    }

    header('Location: /admin/order/' . $order->id . '?alert=success');

});

$router->get('/step-1', function () {
    $settings = \RedBeanPHP\R::load('settings', 1);
    $tickets = $settings->value;

    if ($tickets <= 0) {
        header('Location: /sold-out');
    }

    checkIfTicketsAreOnSale();
    include 'views/common/head.php';
    include 'views/step1.php';
    include 'views/common/footer.php';
});

$router->post('/', function () {
    // POST variables
    $eventTicketQty = getInteger($_POST['eventTicketQty']);
    $ticketEnhancerQty = getInteger($_POST['ticketEnhancerQty']);

    $settings = \RedBeanPHP\R::load('settings', 1);
    $tickets = $settings->value;

    if (((int)$tickets - $eventTicketQty) < 0) {
        header('Location: /step-1?error=tickets');
    }

    // Calculate totals
    $additionalContribution = convertPossibleFloatToCents($_POST['additionalContribution']);
    list($tableTicketQty, $eventTicketQty) = eventPricing($eventTicketQty);
    $eventTicketPrice = convertPossibleFloatToCents($eventTicketQty * $_SERVER['EVENT_TICKET_PRICE']);
    $tableTicketPrice = convertPossibleFloatToCents($tableTicketQty * $_SERVER['TABLE_TICKET_PRICE']);
    $ticketEnhancerPrice = convertPossibleFloatToCents($ticketEnhancerQty * $_SERVER['ENHANCER_TICKET_PRICE']);

    // Sum the cart totals
    $cartTotal = $eventTicketPrice + $tableTicketPrice + $ticketEnhancerPrice + $additionalContribution;
    include 'views/common/head.php';
    include 'views/step2.php';
    include 'views/common/footer.php';
});

$router->post('/checkout', function () {
    //todo this is duplicated and should be handled by an object.
    // POST variables
    $originalTicketQty = $eventTicketQty = getInteger($_POST['eventTicketQty']); // Store original ticket quantity
    $ticketEnhancerQty = getInteger($_POST['ticketEnhancerQty']);

    // Calculate totals
    $additionalContribution = convertPossibleFloatToCents($_POST['additionalContribution']);
    list($tableTicketQty, $eventTicketQty) = eventPricing($eventTicketQty);
    $eventTicketPrice = convertPossibleFloatToCents($eventTicketQty * $_SERVER['EVENT_TICKET_PRICE']);
    $tableTicketPrice = convertPossibleFloatToCents($tableTicketQty * $_SERVER['TABLE_TICKET_PRICE']);
    $ticketEnhancerPrice = convertPossibleFloatToCents($ticketEnhancerQty * $_SERVER['ENHANCER_TICKET_PRICE']);

    // Sum the cart totals
    $cartTotal = $eventTicketPrice + $tableTicketPrice + $ticketEnhancerPrice + $additionalContribution;

    $redirectUuid = $uuid = \Ramsey\Uuid\Uuid::uuid1();

    // Instantiate order object
    $order = R::dispense('orders');

    // Check if credit checkout and valid
    $stripeCustomerToken = null;
    if ($_POST['paymentMethod'] == 0) {
        Stripe::setApiKey($_SERVER['STRIPE_API_SECRET_KEY']);
        $customer = Customer::create([
            'description' => $_POST['firstName'] . ' ' . $_POST['lastName'] . ' - ' . $_POST['email'],
            'source' => $_POST['stripeToken'], // obtained with Stripe.js
            'email' => $_POST['email'],
        ]);
        // Charge the Customer instead of the card:
        $charge = Charge::create([
            'amount' => $cartTotal,
            'currency' => 'usd',
            'description' => date('Y') . ' Dinner in the Woods',
            'customer' => $customer->id,
        ]);
        // make payment
        $order->stripe_token = $charge->id;
        $stripeCustomerToken = $customer->id; // For Guest entry
    }

    $order->ticket_quantity = $originalTicketQty;
    $order->ticket_cents = $eventTicketPrice + $tableTicketPrice;
    $order->enhancer_quantity = $ticketEnhancerQty;
    $order->enhancer_cents = $ticketEnhancerPrice;
    $order->additional_cents = $additionalContribution;
    $order->total_cents = $cartTotal;
    $order->first_name = $_POST['firstName'];
    $order->last_name = $_POST['lastName'];
    $order->email = $_POST['email'];
    $order->address = $_POST['address'];
    $order->city = $_POST['city'];
    $order->state = $_POST['state'];
    $order->zip = $_POST['zip'];
    $order->payment_type = $_POST['paymentMethod'];
    $order->uuid = $uuid->toString();
    $orderId = R::store($order);

    $settings = R::load('settings', 1);
    $settings->value = $settings->value - $originalTicketQty;
    R::store($settings);

    for ($i = 1; $i <= $originalTicketQty; $i++) {
        $uuid = \Ramsey\Uuid\Uuid::uuid1();
        $guest = R::dispense('guests');
        // First guest is the person who went through checkout
        if ($i === 1) {
            $guest->name = $_POST['firstName'] . ' ' . $_POST['lastName'];
            $guest->email = $_POST['email'];
            $guest->phone = $_POST['phone'];
            $guest->stripe_id = $stripeCustomerToken;
        }
        $guest->order_id = $orderId;
        $guest->uuid = $uuid->toString();
        R::store($guest);
        unset($guest, $uuid);
    }


    $orderedItems = [];
    if ($eventTicketQty > 0) {
        array_push($orderedItems, [
            'description' => $eventTicketQty . ' x Dinner tickets',
            'amount' => '$' . number_format(($eventTicketPrice / 100), 2)
        ]);
    }
    if ($tableTicketQty > 0) {
        array_push($orderedItems, [
            'description' => $tableTicketQty . ' x Table',
            'amount' => '$' . number_format(($tableTicketPrice / 100), 2)
        ]);
    }
    if ($ticketEnhancerQty > 0) {
        array_push($orderedItems, [
            'description' => $ticketEnhancerQty . ' x Packs of ticket enhancers',
            'amount' => '$' . number_format(($ticketEnhancerPrice / 100), 2)
        ]);
    }
    if ($additionalContribution > 0) {
        array_push($orderedItems, [
            'description' => 'Additional contribution',
            'amount' => '$' . number_format(($additionalContribution / 100), 2)
        ]);
    }

    // Check if payment was made with Stripe
    if (isset($stripeCustomerToken)) {
        $paymentMethod = 'check_payment';
        $paymentNote = $stripeCustomerToken;
    } else {
        $paymentMethod = 'credit_payment';
        $paymentNote = true;
    }

    $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);
    $client->sendEmailWithTemplate(
        $_SERVER['POSTMARK_FROM'],
        $order->email,
        $_SERVER['POSTMARK_TEMPLATE'],
        [
            'name' => $order->first_name,
            'product_name' => 'Dinner in the Woods ' . date('Y'),
            'date' => date('m-d-Y'),
            'receipt_id' => $order->id,
            'receipt_details' => $orderedItems,
            'total' => '$' . number_format(($order->total_cents / 100), 2),
            'action_manage_guests_url' => 'https://' . $_SERVER['manage-url'] . $order->uuid,
            'action_receipt_url' => 'https://' . $_SERVER['receipt-url'] . $order->uuid,
            $paymentMethod => $paymentNote
        ],
        true, //inline css
        null, //tag
        true, //track opens
        null, //reply to
        null, //cc
        $_SERVER['POSTMARK_FROM'] //bcc
    );

    header('Location: /thank-you/' . $redirectUuid->toString());
});

$router->get('/manage/{uuid}', function ($uuid) {
    $order = R::findOne('orders', ' uuid = ?', [$uuid]);
    $guests = R::findAll('guests', ' order_id = ?', [$order->id]);
    include 'views/common/head.php';
    include 'views/guestlist.php';
    include 'views/common/footer.php';
});

$router->get('/blog', function () {
    include 'views/common/head.php';
    include 'views/blog.php';
    include 'views/common/footer.php';
});

$router->get('/guest/{uuid}', function ($uuid) {
    $guest = R::findOne('guests', ' uuid = ?', [$uuid]);
    include 'views/common/head.php';
    include 'views/guest-manage.php';
    include 'views/common/footer.php';
});

$router->post('/guest/{uuid}', function ($uuid) {
    if ($uuid !== $_POST['uuid']) {
        throw new Exception('Invalid form submission', 400);
    }
    $guest = R::findOne('guests', ' uuid = ?', [$uuid]);

    // Check if credit checkout and valid
    if (isset($_POST['stripeToken'])) {
        Stripe::setApiKey($_SERVER['STRIPE_API_SECRET_KEY']);
        $customer = Customer::create([
            "description" => $guest->name . ' - ' . $guest->email,
            "source" => $_POST['stripeToken'], // obtained with Stripe.js
        ]);

        // make payment
        $guest->stripe_id = $customer->id;
    }

    $guest->phone = $_POST['phone'];
    $guest->restrictions = $_POST['restrictions'];
    R::store($guest);
    header('Location: /guest/' . $guest->uuid . '?alert=success');
});

$router->get('/gallery', function () {
    include 'views/common/head.php';
    include 'views/gallery.php';
    include 'views/common/footer.php';
});


$router->get('/faqs', function () {
    include 'views/common/head.php';
    include 'views/faqs.php';
    include 'views/common/footer.php';
});

$router->get('/parents', function () {
    include 'views/common/head.php';
    include 'views/parents.php';
    include 'views/common/footer.php';
});

$router->get('/thank-you/{uuid}', function ($uuid) {
    $order = R::findOne('orders', ' uuid = ?', [$uuid]);
    $guests = R::findAll('guests', ' order_id = ?', [$order->id]);
    include 'views/common/head.php';
    include 'views/thankyou.php';
    include 'views/common/footer.php';
});

$router->get('/sold-out', function () {
    include 'views/common/head.php';
    include 'views/soldout.php';
    include 'views/common/footer.php';
});

$router->post('/manage/{uuid}', function ($uuid) {
    if ($uuid !== $_POST['uuid']) {
        throw new Exception('Invalid form submission', 400);
    }

    $order = R::findOne('orders', ' uuid = ?', [$uuid]);
    $parametersToSearch = $_POST['guestsArray'];
    array_push($parametersToSearch, $order->id);
    $guests = R::findAll('guests', ' id IN(' . R::genSlots($_POST['guestsArray']) . ') AND order_id = ?',
        $parametersToSearch);
    $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);

    foreach ($guests as $id => $guest) {
        if (isset($_POST['guests'][$id]['name'])) {
            $guest->name = $_POST['guests'][$id]['name'];
            if ($guest->email !== $_POST['guests'][$id]['email'] && !empty($_POST['guests'][$id]['email'])) {
                $guestUuid = \Ramsey\Uuid\Uuid::uuid1();
                $emailGuestInfo = true;
                $guest->stripe_id = ''; // Clear stripe id if email changes
                $guest->uuid = $guestUuid->toString(); // get new UUID if email changes
            } else {
                $emailGuestInfo = false;
            }
            $guest->email = $_POST['guests'][$id]['email'];
            $guest->phone = $_POST['guests'][$id]['phone'];
            $guest->restrictions = $_POST['guests'][$id]['restrictions'];
            R::store($guest);

            if ($emailGuestInfo) {
                $client->sendEmailWithTemplate(
                    $_SERVER['POSTMARK_FROM'],
                    $guest->email,
                    $_SERVER['POSTMARK_GUEST_TEMPLATE'],
                    [
                        'from_name' => $order->first_name . ' ' . $order->last_name,
                        'from_email' => $order->email,
                        'guest_name' => $guest->name,
                        'product_name' => 'Dinner in the Woods ' . date('Y'),
                        'action_manage_guests_url' => 'https://' . $_SERVER['manage-guest-url'] . $guest->uuid,
                    ]
                );
            }
        }
    }

    header('Location: /manage/' . $uuid . '?alert=success');
});

// Run it!
$router->run();