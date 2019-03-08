
    </div>
<footer class="container pt-2 text-muted text-center text-small">
    <div class="row">
        <div class="top-20 col-md-12">
            <p class="mb-1">&copy; <?= date('Y'); ?> Natures Classroom Institute of Wisconsin</p>
            <p class="center">Learning through experience. Growing through expression.<a href="https://www.facebook.com/NaturesClassroomInstitute" style="margin-left:15px;color:#5bb3ff" rel="noopener noreferrer" target="_blank"><span class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></span> Like us on Facebook</a> <a href="https://www.instagram.com/naturesclassroominstitute/" style="margin-left:15px;color:#5bb3ff" rel="noopener noreferrer" target="_blank"><span class="fa fa-instagram fa-fw" aria-hidden="true"></span> Follow us on Instagram</a></p>
        </div>
    </div>
</footer>
</div>


<script src="/js/main.js"></script>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict';

        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');

            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
<script type="application/javascript">
    <?php include 'stripe.php'; ?>
</script>
</body>
</html>
