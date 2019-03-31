<div class="text-center">
    <h4>Nature's Classroom Institute & Montessori School Presents</h4>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/lODWa6DkDaU?rel=0&amp;showinfo=0"
            frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen></iframe>
    <h5>Saturday June 1st, 2019 5:00pm-11:30pm</h5>
    <h5>W336 S8455 Hwy E, Mukwonago, WI 53149</h5>

</div>
<div class="row" style="margin-top: 50px">
    <div class="col-md-12 mb-12">
        <div class="mb-12 text-center">
            <?php
            $aprilIncrease = new DateTime('4/1/2019', new DateTimeZone('America/Chicago'));
            $now = new DateTime('now', new DateTimeZone('America/Chicago'));

            $interval = $now->diff($aprilIncrease);
            if ($interval->invert == 0) { ?>
                <h3>Are you a current NCI family or alum?</h3>
                <a href="/step-1" class="btn btn-success">Yes</a>&nbsp;
                <a href="/notify">No</a>
                <?php
            }else {?>
                <a href="/step-1" class="btn-lg btn-success">Purchase Tickets</a>&nbsp;
            <?php
            }
            ?>

        </div>
        <br/><br/>
    </div>

</div>