<?php
    $currentUrl = getCurrentUrl();

    $authUrl = ($currentUrl == 'login.php' || $currentUrl == 'register.php' || $currentUrl == 'forgot_password.php' || $currentUrl == 'reset_password.php' || $currentUrl == 'change_password.php' || $currentUrl == 'profile.php');
?>
<!-- Latest jQuery form server -->
<script src="https://code.jquery.com/jquery.min.js"></script>

<!-- Bootstrap JS form CDN -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<?php if ($authUrl) { ?>
    <!-- jQuery sticky menu -->
    <script src="../theme/js/owl.carousel.min.js"></script>
    <script src="../theme/js/jquery.sticky.js"></script>

    <!-- jQuery easing -->
    <script src="../theme/js/jquery.easing.1.3.min.js"></script>

    <!-- Main Script -->
    <script src="../theme/js/main.js"></script>

    <!-- Slider -->
    <script type="text/javascript" src="../theme/js/bxslider.min.js"></script>
    <script type="text/javascript" src="../theme/js/script.slider.js"></script>
<?php } else { ?>
    <!-- jQuery sticky menu -->
    <script src="theme/js/owl.carousel.min.js"></script>
    <script src="theme/js/jquery.sticky.js"></script>

    <!-- jQuery easing -->
    <script src="theme/js/jquery.easing.1.3.min.js"></script>

    <!-- Main Script -->
    <script src="theme/js/main.js"></script>

    <!-- Slider -->
    <script type="text/javascript" src="theme/js/bxslider.min.js"></script>
    <script type="text/javascript" src="theme/js/script.slider.js"></script>
<?php } ?>

<script>
        $(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "4000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            <?php
            if (isset($_SESSION['success'])) {
            ?>
                toastr.success(<?php echo '"' . $_SESSION['success'] . '"'; ?>)
            <?php
                unset($_SESSION['success']);
            }
            ?>

            <?php
            if (isset($_SESSION['error'])) {
            ?>
                toastr.error(<?php echo '"' . $_SESSION['error'] . '"'; ?>)
            <?php
                unset($_SESSION['error']);
            }
            ?>
        });
    </script>