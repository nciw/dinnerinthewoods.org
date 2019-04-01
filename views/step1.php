<?php
$aprilIncrease = new DateTime('4/1/2019', new DateTimeZone('America/Chicago'));
$now = new DateTime('now', new DateTimeZone('America/Chicago'));

$interval = $now->diff($aprilIncrease);
if ($interval->invert == 0) { ?>
<div class="alert alert-warning" role="alert">
    Pricing increases on April 1st! Buy tickets now!
</div>
<?php
}
$settings = \RedBeanPHP\R::load('settings', 1);
$tickets = $settings->value;
if ($tickets <= 50) { ?>
    <div class="alert alert-danger" role="alert">
        <strong>Hurry!</strong> Only <?=$tickets?> tickets remaining!
    </div>
<?php }

if (isset($_GET['error']) && $_GET['error'] == 'tickets') { ?>
    <div class="alert alert-warning" role="alert">
        Sorry. There are only <?=$tickets?> remaining. Please select an amount less than or equal to the tickets remaining.
    </div>
    <?php
}
?>
<div class="row">
    <div class="col-md-4 order-md-1 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">FAQs</span>
        </h4>
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What are Ticket Prices?</h6>
                    <small class="text-muted">
                        <table>
                            <tr>
                                <td>A Dinner Ticket</td>
                                <td>$<?=$_SERVER['EVENT_TICKET_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>A Table for 8</td>
                                <td>$<?=$_SERVER['TABLE_TICKET_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>A Pack of 10 Ticket Enhancers</td>
                                <td>$<?=$_SERVER['ENHANCER_TICKET_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>Cabana</td>
                                <td>$<?=$_SERVER['CABANA_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>Childcare (per child)</td>
                                <td>$<?=$_SERVER['CHILDCARE_PRICE']?></td>
                            </tr>
                        </table>

                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is Included with a Ticket?</h6>
                    <small class="text-muted">The purchase of your ticket includes entrance into the event, appetizers, dinner and dessert.
                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is Included with a Table?</h6>
                    <small class="text-muted">Your party of 8 will enjoy two bottles of wine (one red and one white) or non-alcoholic beverage of your choice.</small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What are Ticket Enhancers For?</h6>
                    <small class="text-muted">Ticket enhancers can be used for drinks, the Egg Pull and the Wine Pull.
                        Each pack includes 10 tickets. Here is a breakdown of the cost for each item to help guide
                        how many packs of ticket enhancers you will need for the event:<br/>
                        <br/>Drinks - 4 tickets
                        <br/>Wine Pull - 3 tickets
                        <br/>Egg Pull - 2 tickets
                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is Included with a Cabana?</h6>
                    <small class="text-muted">This private, after dinner party area will be available for you and your party to enjoy a pitcher of our signature cocktail and a gourmet dessert. Each cabana can host up to 8 family members/friends.</small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is Included in Childcare?</h6>
                    <small class="text-muted">Childcare is only offered to NCI-MS enrolled students and their families. Childcare will include pizza and a dessert.
                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Where is the Event?</h6>
                    <small class="text-muted">
                        The event is hosted at Nature's Classroom and located at<br/>
                        <a href="https://goo.gl/maps/LQPvQf9Ndh82" target="_blank">
                            W336 S8455 Hwy E<br/>Mukwonago, WI 53149
                        </a>
                    </small>
                </div>
            </li>

        </ul>

    </div>
    <div class="col-md-8 order-md-2">
        <h4 class="mb-3">Your Order</h4>
        <form class="needs-validation" novalidate method="POST" action="/">

            <div class="mb-3">
                <label for="eventTickets">How many tickets would you like to purchase?</label>
                <p class="text-muted">$<?=$_SERVER['EVENT_TICKET_PRICE']?> for each ticket <strong>or</strong>
                    purchase 8 tickets for a table reservation of $<?=$_SERVER['TABLE_TICKET_PRICE']?></p>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Tickets</div>
                    </div>
                <input type="number" class="form-control" id="eventTicketQty" name="eventTicketQty" value="1">
                </div>
            </div>
            <div class="mb-3">
                <label for="enhancerTickets">How many packs of ticket enhancers would you like?</label>
                <p class="text-muted">$<?=$_SERVER['ENHANCER_TICKET_PRICE']?> per pack. Sold in packs of 10 tickets.</p>
                <input type="number" class="form-control" id="ticketEnhancerQty" name="ticketEnhancerQty">
            </div>
            <div class="mb-3">
                <label for="cabanaReservation">Would you like to reserve a cabana?</label>
                <p class="text-muted">Private cabanas available for $<?=$_SERVER['CABANA_PRICE']?></p>

                <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="yes" name="cabanaReservation" type="radio" value="0" checked required>
                        <label for="no">No</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="yes" name="cabanaReservation" type="radio" value="1" required>
                        <label for="yes">Yes</label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="enhancerTickets">Would you like to make a one-time contribution to support our mission
                    in teaching independence, mastery of self, and the environment?</label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                        <div class="input-group-text">$</div>
                    </div>
                    <input name="additionalContribution" type="text" class="form-control" id="inlineFormInputGroup">
                </div>
            </div>


            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
        </form>
    </div>
</div>