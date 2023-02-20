    <!-- Le javascript
================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../theme/js/jquery-1.7.2.min.js"></script>
    <script src="../../theme/js/excanvas.min.js"></script>
    <script src="../../theme/js/chart.min.js" type="text/javascript"></script>
    <script src="../../theme/js/bootstrap.js"></script>
    <script language="javascript" type="text/javascript" src="../../theme/js/full-calendar/fullcalendar.min.js"></script>

    <script src="../../theme/js/base.js"></script>
    <script src="../../theme/js/signin.js"></script>
    <script src="../../../common/ckeditor/ckeditor.js"></script>

    <!-- Reset page -->
    <script>
        const reset = document.getElementById('reset');

        reset.addEventListener('click', function handleClick(event) {
            event.preventDefault();

            const search = document.getElementById('search');
            search.value = '';

            const url = window.location.href;
            window.location.href = url.split('?')[0];
        });
    </script>

    <!-- Toastr -->
    <script>
        // notification
        $(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": false,
                "positionClass": "toast-bottom-left",
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

