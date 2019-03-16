<div class="row">
    <div class="col-md-12 order-md-1">
        <h3 class="mb-3">Orders <a href="/admin/orders/export" class="btn btn-primary">Order Export</a>&nbsp;<a href="/admin/guests/export" class="btn btn-primary">Guest Export</a></h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Order #</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Tickets</th>
                <th scope="col">Enhancers</th>
                <th scope="col">Total</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order) { ?>
                <tr>
                    <th scope="row"><?= $order->id; ?></th>
                    <td><?= $order->first_name . ' ' . $order->last_name; ?></td>
                    <td><a href="mailto:<?=$order->email?>"><?= $order->email ?></a></td>
                    <td><?=$order->ticket_quantity;?></td>
                    <td><?=$order->enhancer_quantity;?></td>
                    <td>
                        <?php // Stripe payment
                        if (!$order->payment_type) { ?>
                            <a target="_blank" href="https://dashboard.stripe.com/payments/<?=$order->stripe_token?>"><?='$' . number_format(($order->total_cents / 100), 2);?></a>
                        <?php }else {
                         echo '$' . number_format(($order->total_cents / 100), 2);
                        } ?>
                    </td>
                    <td style="text-align: center;">
                        <?php
                        if ($order->cabana_cents > 0) {
                            echo '<span class="badge badge-secondary">Cabana</span><br/>';
                        }
                        if ($order->additional_cents > 0) {
                            echo '<span class="badge badge-info">$ '.number_format(($order->additional_cents / 100), 2) .'</span><br/>';
                        }
                        if ($order->payment_type) {
                            echo '<span class="badge badge-warning">Check</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="/admin/order/<?=$order->id?>" class="btn btn-primary" role="button">View Order</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <br/>
    </div>
</div>