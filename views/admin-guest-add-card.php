<div class="row">
    <div class="col-md-12 order-md-1">
        <?php
        if (isset($_GET['alert']) && $_GET['alert'] == 'success') { ?>
            <div class="alert alert-success" role="alert"><?=$_GET['msg']?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php }?>
        <h3 class="mb-3">Add Credit Card</h3>
        <form method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label for="inputName">Name</label>
                    <input type="input" class="form-control" id="inputName" name="name" value="<?=$guest->name?>">
                    <input type="hidden" class="form-control" id="inputGuestId" name="guestId" value="<?=$guest->id?>">
                </div>
                <div class="form-group">
                    <label for="inputEmail">Email</label>
                    <input type="input" class="form-control" id="inputEmail" name="email" value="<?=$guest->email?>">
                </div>

                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Credit Card</button>
            </div>
        </form>
        <br/>
    </div>
</div>