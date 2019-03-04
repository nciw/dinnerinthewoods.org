<?php

use Bramus\Router\Router;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/functions.php';

// Load environment variables
$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/src');
$dotenv->load();
$dotenv->required([
    'EVENT_TICKET_PRICE',
    'ENHANCER_TICKET_PRICE',
    'DB_HOST',
    'DB_USER',
    'DB_PASS',
    'POSTMARK_API_KEY',
]);

$router = new Router();

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
    $eventTicketPrice = convertPossibleFloatToCents($eventTicketQty * $_SERVER['EVENT_TICKET_PRICE']);
    $ticketEnhancerPrice = convertPossibleFloatToCents($ticketEnhancerQty * $_SERVER['ENHANCER_TICKET_PRICE']);

    // Sum the cart totals
    $cartTotal = $eventTicketPrice + $ticketEnhancerPrice + $additionalContribution;
    include 'views/common/head.php';
    include 'views/step2.php';
    include 'views/common/footer.php';
});


// Run it!
$router->run();