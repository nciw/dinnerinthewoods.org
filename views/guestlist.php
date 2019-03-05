
<div class="row">
    <div class="col-md-12 order-md-1">
        <h3 class="mb-3">Your ticket information</h3>
        <?php
        $i = 0;
        foreach ($guests as $guest) {
            $i++;
        ?>

        <h5 class="mb-3">Guest #<?=$i?> <small><?=(is_null($guest->table)) ? '' : 'Table #' . $guest->table; ?><?=(is_null($guest->paddle)) ? '' : ', Paddle #' . $guest->paddle; ?></small></h5>
        <form class="needs-validation" novalidate method="POST">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="firstName">Full name</label>
                    <input type="text" class="form-control" id="firstName" value="<?=$guest->name?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Email</label>
                    <input type="text" class="form-control" id="email" value="<?=$guest->email?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Phone</label>
                    <input type="text" class="form-control" id="phone" value="<?=$guest->phone?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Childcare?</label>
                    <select class="form-control" name="childcare">
                        <option value="0" <?php if (false == $guest->childcare) { echo 'selected'; } ?>>No</option>
                        <option value="1" <?php if (true == $guest->childcare) { echo 'selected'; } ?>>Yes</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Valet?</label>
                    <select class="form-control" name="valet">
                        <option value="0" <?php if (false == $guest->valet) { echo 'selected'; } ?>>No</option>
                        <option value="1" <?php if (true == $guest->valet) { echo 'selected'; } ?>>Yes</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="lastName">Restrictions?</label>
                    <select class="form-control" name="restrictions">
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
    </div>
</div>