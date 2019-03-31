<?php

/**
 * Returns integer and santitizes bad user data
 *
 * @param $variable
 * @param bool $postiveOnly
 * @return int
 */
function getInteger($variable, $postiveOnly = true)
{
    // Change to integer if not one
    if (!is_int($variable)) {
        $variable = (int)$variable;
    }

    if ($postiveOnly && $variable < 0) {
        $variable = 0;
    }

    return $variable;
}

/**
 * Calculate Event Pricing based on date
 *
 * @param DateTime $date
 * @return array
 * @throws Exception
 */
function getEventPricing($date = null)
{
    $priceIncrease1 = new DateTime('4/1/2019', new DateTimeZone('America/Chicago'));
    $priceIncrease2 = new DateTime('5/1/2019', new DateTimeZone('America/Chicago'));

    if (empty($date)) {
        return [
            $_SERVER['EVENT_TICKET_PRICE_1'],
            $_SERVER['TABLE_TICKET_PRICE_1'],
        ];
    }

    $interval = $date->diff($priceIncrease1);
    if ($interval->invert !== 0) {
        return [
            $_SERVER['EVENT_TICKET_PRICE_2'],
            $_SERVER['TABLE_TICKET_PRICE_2'],
        ];
    }

    $interval = $date->diff($priceIncrease2);
    if ($interval->invert !== 0) {
        return [
            $_SERVER['EVENT_TICKET_PRICE_3'],
            $_SERVER['TABLE_TICKET_PRICE_3'],
        ];
    }else {
        return [
            $_SERVER['EVENT_TICKET_PRICE_1'],
            $_SERVER['TABLE_TICKET_PRICE_1'],
        ];
    }
}

/**
 * Converts possible float or string to cents
 *
 * @param $variable
 * @return int
 */
function convertPossibleFloatToCents($variable)
{
    $variable = intval(strval(floatval(preg_replace("/[^0-9.]/", "", str_replace(',', '.', $variable))) * 100));

    return $variable;
}


/**
 * Show shopping cart line item on form
 *
 * @param $name
 * @param $price
 */
function shoppingCartLineItem($name, $price, $description = '')
{
    if ($price > 0) {
        echo '<li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">' . $name . '</h6>
                    <small class="text-muted">' . $description . '</small>
                </div>
                <span class="text-muted">' . '$' . number_format(($price / 100), 2) . '</span>
            </li>';
    }

}

/**
 * Calculate table ticket price and event tickets based on quantity
 *
 * @param $qty integer Quantity of event tickets purchased
 * @param $price integer Price of event tickets
 * @param $tablePrice integer Price of table
 * @return array
 */
function eventPricing($qty)
{
    $tableQty = 0;

    // If pricing is 8 or more then we need to factor in table reservations
    if ($qty > 7) {
        $tableQty = (int)($qty / 8);
        $eventQty = $qty - ($tableQty * 8);
    } else {
        $eventQty = $qty;
    }

    return [$tableQty, $eventQty];
}

function shoppingCartTotal($price)
{
    echo '<li class="list-group-item d-flex justify-content-between">
                <span>Total</span>
                <strong>' . '$' . number_format(($price / 100), 2) . '</strong>
            </li>';
}

function checkIfTicketsAreOnSale()
{
    $todaysDate = new DateTime('now', new DateTimeZone('America/Chicago'));
    $dateOnSale = new DateTime('3/15/2019 8:00am', new DateTimeZone('America/Chicago'));
    $interval = $todaysDate->diff($dateOnSale);

    if ($interval->days >= 0 && $interval->invert === 0) {
        header('Location: /notify?d=' . $dateOnSale->format('c'));
    }

}