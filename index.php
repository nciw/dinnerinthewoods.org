<?php

use Bramus\Router\Router;
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
    'EVENT_TICKET_PRICE',
    'ENHANCER_TICKET_PRICE',
    'CHILDCARE_PRICE',
    'CABANA_PRICE',
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASS',
    'POSTMARK_API_KEY',
    'STRIPE_API_SECRET_KEY',
    'STRIPE_API_PUBLIC_KEY',
]);

$_SERVER['receipt-url'] = $_SERVER['HTTP_HOST']."/thank-you/";
$_SERVER['manage-url'] = $_SERVER['HTTP_HOST']."/manage/";

$router = new Router();
$r = R::setup('mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'], $_SERVER['DB_USER'], $_SERVER['DB_PASS']);

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

// Static route: / (homepage)
$router->get('/', function () {
    $settings = \RedBeanPHP\R::load('settings', 1);
    $tickets = $settings->value;

    if ($tickets <= 0) {
        header('Location: /sold-out');
    }

    include 'views/common/head.php';
    include 'views/family.php';
    include 'views/common/footer.php';
});

$router->get('/notify', function () {
    include 'views/common/head.php';
    include 'views/notify.php';
    include 'views/common/footer.php';
});

$router->get('/step-1', function () {
    include 'views/common/head.php';
    include 'views/step1.php';
    include 'views/common/footer.php';
});

$router->post('/', function () {
    // POST variables
    $eventTicketQty = getInteger($_POST['eventTicketQty']);
    $ticketEnhancerQty = getInteger($_POST['ticketEnhancerQty']);

    // Calculate totals
    $additionalContribution = convertPossibleFloatToCents($_POST['additionalContribution']);
    $cabanaReservation = convertPossibleFloatToCents($_POST['cabanaReservation']);
    list($tableTicketQty, $eventTicketQty) = eventPricing($eventTicketQty);
    $eventTicketPrice = convertPossibleFloatToCents($eventTicketQty * $_SERVER['EVENT_TICKET_PRICE']);
    $tableTicketPrice = convertPossibleFloatToCents($tableTicketQty * $_SERVER['TABLE_TICKET_PRICE']);
    $ticketEnhancerPrice = convertPossibleFloatToCents($ticketEnhancerQty * $_SERVER['ENHANCER_TICKET_PRICE']);

    // Sum the cart totals
    $cartTotal = $eventTicketPrice + $tableTicketPrice + $ticketEnhancerPrice + $additionalContribution + ($cabanaReservation * $_SERVER['CABANA_PRICE']);
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
    $cabanaReservation = $_POST['cabanaReservation'] > 0 ? convertPossibleFloatToCents($_SERVER['CABANA_PRICE']) : 0;

    // Sum the cart totals
    $cartTotal = $eventTicketPrice + $tableTicketPrice + $ticketEnhancerPrice + $additionalContribution + $cabanaReservation;

    $redirectUuid = $uuid = \Ramsey\Uuid\Uuid::uuid1();

    // Instantiate order object
    $order = R::dispense('orders');

    // Check if credit checkout and valid
    $stripeCustomerToken = null;
    if ($_POST['paymentMethod'] == 0) {
        Stripe::setApiKey($_SERVER['STRIPE_API_SECRET_KEY']);
        $customer = Customer::create([
            "description" => $_POST['firstName'] . ' ' . $_POST['lastName'] . ' - ' . $_POST['email'],
            "source" => $_POST['stripeToken'], // obtained with Stripe.js
        ]);
        // Charge the Customer instead of the card:
        $charge = Charge::create([
            'amount' => $cartTotal,
            'currency' => 'usd',
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
    $order->cabana_cents = $cabanaReservation;
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

    $client = new Postmark\PostmarkClient($_SERVER['POSTMARK_API_KEY']);

    /**
    $additionalContribution = convertPossibleFloatToCents($_POST['additionalContribution']);
    list($tableTicketQty, $eventTicketQty) = eventPricing($eventTicketQty);
    $eventTicketPrice = convertPossibleFloatToCents($eventTicketQty * $_SERVER['EVENT_TICKET_PRICE']);
    $tableTicketPrice = convertPossibleFloatToCents($tableTicketQty * $_SERVER['TABLE_TICKET_PRICE']);
    $ticketEnhancerPrice = convertPossibleFloatToCents($ticketEnhancerQty * $_SERVER['ENHANCER_TICKET_PRICE']);
    $cabanaReservation = $_POST['cabanaReservation'] > 0 ? convertPossibleFloatToCents($_SERVER['CABANA_PRICE']) : 0;

     */
    $orderedItems = [];
    if ($eventTicketQty > 0) {
        array_push($orderedItems, ['description' => $eventTicketQty . ' x Dinner tickets', 'amount' => '$' . number_format(($eventTicketPrice / 100), 2)]);
    }
    if ($tableTicketQty > 0) {
        array_push($orderedItems, ['description' => $tableTicketQty . ' x Table', 'amount' => '$' . number_format(($tableTicketPrice / 100), 2)]);
    }
    if ($ticketEnhancerQty > 0) {
        array_push($orderedItems, ['description' => $ticketEnhancerQty . ' x Packs of ticket enhancers', 'amount' => '$' . number_format(($ticketEnhancerPrice / 100), 2)]);
    }
    if ($cabanaReservation > 0) {
        array_push($orderedItems, ['description' => 'Cabana reservation', 'amount' => '$' . number_format(($cabanaReservation / 100), 2)]);
    }
    if ($additionalContribution > 0) {
        array_push($orderedItems, ['description' => 'Additional contribution', 'amount' => '$' . number_format(($additionalContribution / 100), 2)]);
    }

    // Check if payment was made with Stripe
    if (isset($stripeCustomerToken)) {
        $paymentMethod = 'credit_payment';
        $paymentNote = $stripeCustomerToken;
    }else{
        $paymentMethod = 'check_payment';
        $paymentNote = true;
    }

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
            'action_manage_guests_url' => '//'.$_SERVER['manage-url'] . $order->uuid,
            'action_receipt_url' => '//'.$_SERVER['receipt-url'] . $order->uuid,
            $paymentMethod => $paymentNote
        ]
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
    $guests = R::findAll('guests', ' id IN(' . R::genSlots($_POST['guestsArray']) . ') AND order_id = ?', $parametersToSearch);

    foreach ($guests as $id => $guest) {
        if (!empty($_POST['guests'][$id]['name']) &&
            !empty($_POST['guests'][$id]['email']) &&
            !empty($_POST['guests'][$id]['phone'])) {
            $guest->name = $_POST['guests'][$id]['name'];
            $guest->email = $_POST['guests'][$id]['email'];
            $guest->phone = $_POST['guests'][$id]['phone'];
            $guest->childcare = $_POST['guests'][$id]['childcare'];
            $guest->valet = $_POST['guests'][$id]['valet'];
            $guest->restrictions = $_POST['guests'][$id]['restrictions'];
            R::store($guest);
        }
    }

    header('Location: /manage/' . $uuid . '?alert=success');
});

// Run it!
$router->run();