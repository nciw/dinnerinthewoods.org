
<div class="row">
    <div class="col-md-4 order-md-2 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your cart</span>
        </h4>
        <ul class="list-group mb-3">
            <?=shoppingCartLineItem('Table', $tableTicketPrice, $tableTicketQty . ' x table');?>
            <?=shoppingCartLineItem('Dinner', $eventTicketPrice, $eventTicketQty . ' x tickets');?>
            <?=shoppingCartLineItem('Ticket Enhancers', $ticketEnhancerPrice, $ticketEnhancerQty . ' x enhancers');?>
            <?=shoppingCartLineItem('Add\'l Contribution', $additionalContribution);?>
            <?=shoppingCartTotal($cartTotal);?>

        </ul>

    </div>
    <div class="col-md-8 order-md-1">
        <h4 class="mb-3">Billing address</h4>
        <form class="needs-validation" novalidate method="POST" action="/checkout" id="payment-form">
            <?php
            $eventTicketQty = getInteger($_POST['eventTicketQty']);
            $ticketEnhancerQty = getInteger($_POST['ticketEnhancerQty']);
            $additionalContribution = getInteger($_POST['additionalContribution']);
            ?>
            <input type="hidden" name="eventTicketQty" value="<?=$eventTicketQty?>" />
            <input type="hidden" name="ticketEnhancerQty" value="<?=$ticketEnhancerQty?>" />
            <input type="hidden" name="additionalContribution" value="<?=$additionalContribution?>" />
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName">First name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" value="" required>
                    <div class="invalid-feedback">
                        Valid first name is required.
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastName">Last name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" value="" required>
                    <div class="invalid-feedback">
                        Valid last name is required.
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" required>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>
            <div class="mb-3">
                <label for="phone">Phone</label>
                <input type="phone" class="form-control" id="phone" name="phone" required>
                <div class="invalid-feedback">
                    Please enter a valid phone number.
                </div>
            </div>
            <div class="mb-3">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                <div class="invalid-feedback">
                    Please enter your address.
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="" required>
                    <div class="invalid-feedback">
                        City required.
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <label for="state">State</label>
                    <select class="custom-select d-block w-100" id="state" name="state" required>
                        <option value="AL">AL</option>
                        <option value="AK">AK</option>
                        <option value="AR">AR</option>
                        <option value="AZ">AZ</option>
                        <option value="CA">CA</option>
                        <option value="CO">CO</option>
                        <option value="CT">CT</option>
                        <option value="DC">DC</option>
                        <option value="DE">DE</option>
                        <option value="FL">FL</option>
                        <option value="GA">GA</option>
                        <option value="HI">HI</option>
                        <option value="IA">IA</option>
                        <option value="ID">ID</option>
                        <option value="IL">IL</option>
                        <option value="IN">IN</option>
                        <option value="KS">KS</option>
                        <option value="KY">KY</option>
                        <option value="LA">LA</option>
                        <option value="MA">MA</option>
                        <option value="MD">MD</option>
                        <option value="ME">ME</option>
                        <option value="MI">MI</option>
                        <option value="MN">MN</option>
                        <option value="MO">MO</option>
                        <option value="MS">MS</option>
                        <option value="MT">MT</option>
                        <option value="NC">NC</option>
                        <option value="NE">NE</option>
                        <option value="NH">NH</option>
                        <option value="NJ">NJ</option>
                        <option value="NM">NM</option>
                        <option value="NV">NV</option>
                        <option value="NY">NY</option>
                        <option value="ND">ND</option>
                        <option value="OH">OH</option>
                        <option value="OK">OK</option>
                        <option value="OR">OR</option>
                        <option value="PA">PA</option>
                        <option value="RI">RI</option>
                        <option value="SC">SC</option>
                        <option value="SD">SD</option>
                        <option value="TN">TN</option>
                        <option value="TX">TX</option>
                        <option value="UT">UT</option>
                        <option value="VT">VT</option>
                        <option value="VA">VA</option>
                        <option value="WA">WA</option>
                        <option value="WI" selected>WI</option>
                        <option value="WV">WV</option>
                        <option value="WY">WY</option>
                    </select>

                    <div class="invalid-feedback">
                        Please provide a valid state.
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <label for="zip">Zip</label>
                    <input type="text" class="form-control" id="zip" name="zip" required>
                    <div class="invalid-feedback">
                        Zip code required.
                    </div>
                </div>
            </div>
            <hr class="mb-4">

            <h4 class="mb-3">Payment</h4>

            <div class="d-block my-3">
                <div class="custom-control custom-radio">
                    <input id="credit" name="paymentMethod" type="radio" value="0" checked required>
                    <label for="credit">Credit card</label>
                </div>
                <div class="custom-control custom-radio">
                    <input id="check" name="paymentMethod" type="radio" value="1" required>
                    <label for="debit">Check/Cash</label>
                </div>
            </div>
            <div class="row" id="checkDetails" style="display: none">
                <div class="col-md-12 mb-3">
                    <p>Mail or drop off payment within seven days of submitting this form or your tickets will be released
                    for others to purchase<br/>
                    <br/>
                    Mail to:<br/>
                    Attn: Dinner in the Woods<br/>
                    Nature's Classroom<br/>
                    PO Box 660<br/>
                    Mukwonago, WI 53149
                    </p>
                </div>
            </div>
            <div id="creditDetails">
                <label for="card-element">
                    Credit or debit card
                </label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>
            </div>
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Checkout</button>
        </form>
        <br/>
    </div>
</div>