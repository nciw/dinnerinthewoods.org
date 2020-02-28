
    </div>
<footer class="container pt-2 text-muted text-center text-small">
    <div class="row">
        <div class="top-20 col-md-12">
            <p class="mb-1">&copy; <?= date('Y'); ?> Nature's Classroom Institute of Wisconsin. All rights reserved.</p>
            <p class="center">Learning through experience. Growing through expression.<a href="https://www.facebook.com/NaturesClassroomInstitute" style="margin-left:15px;color:#5bb3ff" rel="noopener noreferrer" target="_blank"><span class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></span> Like us on Facebook</a> <a href="https://www.instagram.com/naturesclassroominstitute/" style="margin-left:15px;color:#5bb3ff" rel="noopener noreferrer" target="_blank"><span class="fa fa-instagram fa-fw" aria-hidden="true"></span> Follow us on Instagram</a></p>
        </div>
    </div>
</footer>
</div>

    <script type="application/javascript">
        $('#enhancerModal').on('shown.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('name')
            var guestid = button.data('guestid')
            var modal = $(this)
            modal.find('.modal-title').text('Add Enhancers for ' + recipient)
            modal.find('.modal-body #inputGuestId').val(guestid)
            modal.find('.modal-body #inputEnhancers').val('').focus()
        });

        $("#checkout-body").on('input', '.txtCal', function () {
            var calculated_total_sum = 0;

            $("#checkout-body .txtCal").each(function () {
                var get_textbox_value = $(this).val();
                if ($.isNumeric(get_textbox_value)) {
                    calculated_total_sum += parseFloat(get_textbox_value);
                }
            });

            $('#totalCharge').val(calculated_total_sum)
        });
    </script>
    <script type="application/javascript">
        <?php include 'stripe.php'; ?>
    </script>
</body>
</html>
