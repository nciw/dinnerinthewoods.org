<div class="row">
    <div class="col-md-12 mb-12">
        <div class="mb-12 text-center">
            <br/><br/>
            <h3>Sorry - this event is postponed at the moment.</h3>
            <?php
            if (isset($_GET['d'])) {
                $date = new DateTime($_GET['d']);
                echo '<h5>You will be notified when the tickets are on sale.</h5>';
                //echo '<h5>VIP tickets go on sale ' . $date->format('F jS, Y \a\t h:ia') . '</h5>';
            }
            ?>
            <h5>Early bird tickets will go on sale April 1st, 2020 at 8:00am</h5>
            <!-- Begin Mailchimp Signup Form -->
            <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
            <style type="text/css">
                #mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
                /* Add your own Mailchimp form style overrides in your site stylesheet or in this style block.
                   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
            </style>
            <div id="mc_embed_signup">
                <form action="https://discovernci.us9.list-manage.com/subscribe/post?u=191d352658045bb98e2c71587&amp;id=b56299d652" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <h2>Sign up to be notified when tickets are available to the public!</h2>
                        <div class="mc-field-group">
                            <label for="mce-EMAIL">Email Address </label>
                            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                        </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_191d352658045bb98e2c71587_b56299d652" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                </form>
            </div>
            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
            <!--End mc_embed_signup-->
        </div>
        <br/>
    </div>

</div>