<div class="row">
    <div class="col-md-12 mb-12">
        <div class="mb-12 text-center">
            <br/><br/>
            <h1>Thank You!</h1>
            <p>Please <a href="javascript:window.print()">print this page</a> for your records.</p>
        </div>
        <div class="col-md-12 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Receipt #<?= $order->id ?></span>
            </h4>
            <?php
            if ($order->payment_type == 1) { ?>
                <p>Mail or drop off payment within seven days of submitting this form or your tickets will be released
                    for others to purchase<br/>
                    <br/>
                    Mail to:<br/>
                    Attn: Dinner in the Woods<br/>
                    Nature's Classroom<br/>
                    PO Box 660<br/>
                    Mukwonago, WI 53149
                </p>
            <?php } else { ?>
                <p>Your payment will be processed by our payment processor Stripe
                    <small>(Stripe # <?= $order->stripe_token ?>)</small>
                </p>
            <?php } ?>
            <p>
                Purchased by:<br/>
                <?= $order->first_name . ' ' . $order->last_name . ' (' . $order->email . ')' ?> <br/>
                <?= $order->address ?><br/>
                <?= $order->city . ', ' . $order->state . ' ' . $order->zip ?><br/>
            </p>
            <ul class="list-group mb-3">
                <?= shoppingCartLineItem('Dinner', $order->ticket_cents, $order->ticket_quantity . ' x tickets'); ?>
                <?= shoppingCartLineItem('Cabana', $order->cabana_cents); ?>
                <?= shoppingCartLineItem('Ticket Enhancers', $order->enhancer_cents, $order->enhancer_quantity . ' x enhancers'); ?>
                <?= shoppingCartLineItem('Add\'l Contribution', $order->additional_cents); ?>
                <?= shoppingCartTotal($order->total_cents); ?>

            </ul>

            <p>We will send you an email notification before the event as a reminder. If you purchased additional
                tickets for your
                guests you can <a href="/manage/<?= $order->uuid ?>">manage your guests</a>. If you have any questions
                please email <a href="mailto:deepa@nciw.org">deepa@nciw.org</a></p>
            <p class="text-center"><a href="/manage/<?= $order->uuid ?>" class="btn btn-lg btn-success">Manage
                    Guests</a></p>
        </div>

    </div>

</div>