<div class="row">
    <div class="col-md-4 order-md-1 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">FAQs</span>
        </h4>
        <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">How much is the event?</h6>
                    <small class="text-muted">
                        <table>
                            <tr>
                                <td>Dinner ticket</td>
                                <td>$<?=$_SERVER['EVENT_TICKET_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>Table for eight</td>
                                <td>$<?=$_SERVER['TABLE_TICKET_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>Ticket enhancer (10 pack)</td>
                                <td>$<?=$_SERVER['ENHANCER_TICKET_PRICE']?></td>
                            </tr>
                            <tr>
                                <td>Childcare</td>
                                <td>$<?=$_SERVER['CHILDCARE_PRICE']?> per child</td>
                            </tr>
                            <tr>
                                <td>Cabana</td>
                                <td>$<?=$_SERVER['CABANA_PRICE']?></td>
                            </tr>
                        </table>

                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is included with a ticket?</h6>
                    <small class="text-muted">Entrance into the event, appetizers, dessert, and dinner is
                        included. Ticket
                        enhancers may be purchased for drinks and other onsite activities.
                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is a cabana?</h6>
                    <small class="text-muted">
                        A cabana rental is a private rustically decorated space for you to continue
                        enjoying the Dinner in the Woods experience after the main dinner. A pitcher of
                        signature cocktail and a gourmet dessert is provided with the rental.
                    </small>
                </div>
            </li>

            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is included with a table?</h6>
                    <small class="text-muted">Your party will enjoy two bottles of complimentary wine.</small>
                </div>
            </li>


            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">What is a ticket enhancer?</h6>
                    <small class="text-muted">Ticket enhancers are used for drinks, the egg pull, and the wine
                        pull while at the event. It is sold as a 10 pack of tickets.<br/>
                        <br/>Drinks - 4 tickets
                        <br/>Wine Pull - 3 tickets
                        <br/>Egg Pull - 2 tickets
                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Is childcare offered?</h6>
                    <small class="text-muted">Only to NCM enrolled students. Childcare will include pizza and
                        dessert.
                    </small>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div>
                    <h6 class="my-0">Where is the event?</h6>
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
        <form class="needs-validation" novalidate method="POST">

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