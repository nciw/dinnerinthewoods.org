<div class="modal" id="enhancerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add Enhancers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEnhancers">How many enhancers packs are given (10 per pack)?</label>
                        <input type="number" class="form-control" id="inputEnhancers" name="enhancerQty" value="0">
                        <input type="hidden" class="form-control" id="inputGuestId" name="guestId" value="0">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Enhancers</button>
            </div>
            </form>
        </div>
    </div>
</div>
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
        <h3 class="mb-3">Guest List
            <a href="/admin/orders/" class="btn btn-primary">Order List</a>
            <a href="/admin/orders/export" class="btn btn-primary">Order Export</a>
            <a href="/admin/guests/export" class="btn btn-primary">Guest Export</a>
        </h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col"></th>
                <th scope="col">Email</th>
                <th scope="col">Paddle #</th>
                <th scope="col">Enhancers</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($guests as $guest) { ?>
                <tr>
                    <td>
                        <?= $guest->name; ?>
                    </td>
                    <td>
                        <?php if ($guest->stripe_id) { ?>
                            <span class="badge badge-success">Card on file</span>
                        <?php } ?>
                        <?php if ($guest->checkout_cents) { ?>
                            <span class="badge badge-primary">Checked Out</span>
                        <?php } ?>
                    </td>
                    <td><?= $guest->email; ?></td>
                    <td><?= $guest->paddle; ?></td>
                    <td>
                        <?= $guest->enhancerQty;?>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <button class="dropdown-item" data-toggle="modal" data-target="#enhancerModal" data-name="<?=$guest->name;?>" data-guestid="<?=$guest->id;?>">Add Enhancers</button>
                                <?php if (!$guest->stripe_id) { ?>
                                    <a class="dropdown-item" href="/admin/guest/add-card/<?=$guest->id?>">Add Credit Card</a>
                                <?php } else { ?>
                                    <a class="dropdown-item" href="/admin/guest/delete-card/<?=$guest->id?>">Delete Credit Card</a>
                                <?php } ?>
                                <a class="dropdown-item" href="/admin/guest/checkout/<?=$guest->id?>">Checkout</a>

                            </div>
                        </div>

                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <br/>
    </div>
</div>