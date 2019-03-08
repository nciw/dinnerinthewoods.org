
<?php
if (isset($_GET['alert']) && $_GET['alert'] == 'success') { ?>
    <div class="alert alert-success" role="alert">Guest list is saved!</div>

<?php }?>
<div class="row">
    <div class="col-md-12 order-md-1">
        <h3 class="mb-3">Your ticket information</h3>
        <p>When you update your guest information they will be signed up for event emails and also receive a login to manage their profile. This will allow
        them to save their credit card information for faster checkout at the event. The email that we send them will include your contact information.</p>
        <form class="needs-validation" novalidate method="POST">
        <input type="hidden" name="uuid" value="<?=$order->uuid?>">
        <?php
        $i = 0;
        foreach ($guests as $guest) {
            $i++;
        ?>
        <input type="hidden" name="guests[<?=$i?>][id]" value="<?=$guest->id;?>" >
        <input type="hidden" name="guestsArray[]" value="<?=$guest->id;?>" >
        <h5 class="mb-3">Guest #<?=$i?> <small><?=(is_null($guest->table)) ? '' : 'Table #' . $guest->table; ?><?=(is_null($guest->paddle)) ? '' : ', Paddle #' . $guest->paddle; ?></small></h5>
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="name">Full name</label>
                    <input name="guests[<?=$i?>][name]" type="text" class="form-control" id="name" value="<?=$guest->name?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Email</label>
                    <input name="guests[<?=$i?>][email]" type="text" class="form-control" id="email" value="<?=$guest->email?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Phone</label>
                    <input name="guests[<?=$i?>][phone]" type="text" class="form-control" id="phone" value="<?=$guest->phone?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="childcare">Childcare?</label>
                    <select class="form-control" name="guests[<?=$i?>][childcare]">
                        <option value="0" <?php if (false == $guest->childcare) { echo 'selected'; } ?>>No</option>
                        <option value="1" <?php if (true == $guest->childcare) { echo 'selected'; } ?>>Yes</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="restrictions">Food restrictions?</label>
                    <select class="form-control" name="guests[<?=$i?>][restrictions]">
                        <option value="0" <?php if (0 == $guest->restrictions) { echo 'selected'; } ?>>None</option>
                        <option value="1" <?php if (1 == $guest->restrictions) { echo 'selected'; } ?>>Vegetarian</option>
                        <option value="2" <?php if (2 == $guest->restrictions) { echo 'selected'; } ?>>Vegan</option>
                    </select>
                </div>
            </div>
            <?php } ?>
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Save</button>
        </form>
        <br/>
    </div>
</div>