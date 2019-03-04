<?php

use Bramus\Router\Router;
use RedBeanPHP\R;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/functions.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/src');
$dotenv->load();
$dotenv->required([
    'EVENT_TICKET_PRICE',
    'ENHANCER_TICKET_PRICE',
    'DB_HOST',
    'DB_NAME',
    'DB_USER',
    'DB_PASS',
    'POSTMARK_API_KEY',
]);

$router = new Router();
$r = R::setup('mysql:host=' . $_SERVER['DB_HOST'] . ';dbname=' . $_SERVER['DB_NAME'], $_SERVER['DB_USER'], $_SERVER['DB_PASS']);

// Custom 404 Handler
$router->set404(function () {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    echo '404, route not found!';
});

// Static route: / (homepage)
$router->get('/', function () {
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

    $uuid = \Ramsey\Uuid\Uuid::uuid1();

    // Instantiate order object
    $order = R::dispense('orders');

    // Check if credit checkout and valid
    if ($_POST['paymentMethod'] == 0) {
        // make payment
        $order->stripe_token = '1234';
        $stripeCustomerToken = '1234'; // For Guest entry
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
            $guest->stripe_id = $stripeCustomerToken;
        }
        $guest->order_id = $orderId;
        $guest->uuid = $uuid->toString();
        R::store($guest);
        unset($guest, $uuid);
    }
});


// Run it!
$router->run();