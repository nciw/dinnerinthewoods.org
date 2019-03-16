<div class="row">
    <div class="col-md-12 order-md-1">
        <h3 class="mb-3">Order #<?= $order->id ?> Details
            <a href="/admin/orders" class="btn btn-primary btn-small">Back to Orders</a></h3>
        <p><strong>Purchased by:</strong><br/>
            <?= $order->first_name . ' ' . $order->last_name; ?><br/>
            <?= $order->address; ?><br/>
            <?= $order->city . ', ' . $order->state . ' ' . $order->zip; ?><br/>
            <a href="mailto:<?= $order->email; ?>"><?= $order->email; ?></a><br/>
        </p>
        <p><strong>Technical Details:</strong><br/>
            <?php
            if ($order->payment_type) {
                echo 'Payment by check';
            } else {
                echo 'Paid with credit card: ' . '<a href="https://dashboard.stripe.com/payments/' . $order->stripe_token . '" target="_blank">' . $order->stripe_token . '</a>';
            } ?>
        </p>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">Ticket Qty</th>
                <th scope="col">Ticket $</th>
                <th scope="col">Enhancer Qty</th>
                <th scope="col">Enhancer $</th>
                <th scope="col">Add'tl Contribution</th>
                <th scope="col">Cabana</th>
                <th scope="col">Total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= $order->ticket_quantity; ?></td>
                <td><?= '$' . number_format(($order->ticket_cents / 100), 2); ?></td>
                <td><?= $order->enhancer_quantity; ?></td>
                <td><?= '$' . number_format(($order->enhancer_cents / 100), 2); ?></td>
                <td><?= '$' . number_format(($order->additional_cents / 100), 2); ?></td>
                <td><?= '$' . number_format(($order->cabana_cents / 100), 2); ?></td>
                <td><?= '$' . number_format(($order->total_cents / 100), 2); ?></td>
            </tr>
            </tbody>
        </table>
        <br/>

        <h3>Guests</h3>
        <form method="POST">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Guest Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Table #</th>
                    <th scope="col">Paddle #</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($guests as $guest) { ?>
                    <input type="hidden" name="guestsArray[]" value="<?= $guest->id; ?>"/>
                    <tr>
                        <td><?= $guest->name; ?></td>
                        <td><?= $guest->email; ?></td>
                        <td><?= $guest->phone; ?></td>
                        <td><input type="text" class="form-control" name="table[<?=$guest->id?>]" value="<?= $guest->table; ?>"/></td>
                        <td><input type="text" class="form-control" name="paddle[<?=$guest->id?>]" value="<?= $guest->paddle; ?>"/></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Save</button>
        </form>
        <br/>
        <br/>

    </div>
</div>