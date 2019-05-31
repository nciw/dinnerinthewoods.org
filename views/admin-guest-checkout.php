<div class="row">
    <div class="col-md-12 order-md-1">
        <h3 class="mb-3">Checkout</h3>
        <div class="alert alert-primary" role="alert">
            Enhancer price and additional prices are added together and charged if credit card is on file.
        </div>
        <form method="POST">
            <div class="checkout-body" id="checkout-body">
                <div class="form-group">
                    <label for="inputName">Name</label>
                    <input type="input" class="form-control" id="inputName" name="name" value="<?=$guest->name?>" readonly>
                    <input type="hidden" class="form-control" id="inputGuestId" name="guestId" value="<?=$guest->id?>">
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="input" class="form-control" id="inputEmail" name="email" value="<?=$guest->email?>" readonly>
                </div>
                <?php
                $chargeVerbiage = 'charge';
                if (empty($guest->stripe_id)) {
                    $chargeVerbiage = 'collect';
                    ?>
                    <div class="alert alert-warning" role="alert">
                        There is no credit card on file. <strong>Please collect a check or cash.</strong>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label for="total">Enhancers purchased during the event: <?=$guest->enhancer_qty?></label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input name="enhancerCharge" type="text" class="form-control txtCal" id="inlineFormInputGroup" value="<?=$guest->enhancer_qty * $_SERVER['ENHANCER_TICKET_PRICE_SINGLE']?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="total">Additional price for auctions</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input name="auctionCharge" type="text" class="form-control txtCal" id="inlineFormInputGroup">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="total">Total price to <?=$chargeVerbiage?></label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">$</div>
                        </div>
                        <input name="totalCharge" type="text" class="form-control" id="totalCharge" value="<?=$guest->enhancer_qty * $_SERVER['ENHANCER_TICKET_PRICE_SINGLE']?>" readonly>
                    </div>
                </div>
                <?php
                if (empty($guest->stripe_id)) {?>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="sendViaStripe" name="sendViaStripe">
                        <label class="form-check-label" for="sendViaStripe">Send invoice via stripe?</label>
                    </div>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Checkout</button>
            </div>
        </form>
        <br/>
    </div>
</div>