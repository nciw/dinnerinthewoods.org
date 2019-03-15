
<?php
if (isset($_GET['alert']) && $_GET['alert'] == 'success') { ?>
    <div class="alert alert-success" role="alert">Thank You for submitting your guest information.</div>

<?php }?>
<div class="row">
    <div class="col-md-12 order-md-1">
        <h3 class="mb-3">Your information</h3>
        <p>Note: childcare is only available for NCM families for $25/child. Payment will be taken on the day of the event.</p>
        <form class="needs-validation" novalidate method="POST" id="payment-form">
        <input type="hidden" name="uuid" value="<?=$guest->uuid?>">

        <h5 class="mb-3"><?=$guest->name?> <small><?=(empty($guest->table)) ? '' : 'Table #' . $guest->table; ?><?=(empty($guest->paddle)) ? '' : ', Paddle #' . $guest->paddle; ?></small></h5>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="lastName">Phone</label>
                    <input name="phone" type="text" class="form-control" id="phone" value="<?=$guest->phone?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="childcare">Childcare?</label>
                    <select class="form-control" name="childcare">
                        <option value="0" <?php if (false == $guest->childcare) { echo 'selected'; } ?>>No</option>
                        <option value="1" <?php if (true == $guest->childcare) { echo 'selected'; } ?>>Yes</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="restrictions">Food preferences?</label>
                    <select class="form-control" name="restrictions">
                        <option value="0" <?php if (0 == $guest->restrictions) { echo 'selected'; } ?>>None</option>
                        <option value="1" <?php if (1 == $guest->restrictions) { echo 'selected'; } ?>>Vegetarian</option>
                        <option value="2" <?php if (2 == $guest->restrictions) { echo 'selected'; } ?>>Vegan</option>
                    </select>
                </div>
            </div>

            <h5>Credit Card Details</h5>

            <?php
            if (empty($guest->stripe_id)) {
            ?>
            <p>Adding your credit card number will make it quicker for you to buy drink tickets, egg tickets, and checkout
                quicker with live auction items. <i>It is not required to save.</i></p>
            <div id="creditDetails">
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>
            </div><br/>
            <div>
                <input id="check" type="checkbox" value="1" > Check here if you do not want to store your credit card.
                <input id="credit" type="hidden" value="1" >
            </div>
            <?php }else {
                echo '<p>Your credit card details are stored safely with our payment processor Stripe</p>';
            } ?>
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Save</button>
        </form>
        <br/><br/>
    </div>
</div>