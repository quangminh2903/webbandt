<?php
    require_once '../../common/configs/connect.php';
    require '../../common/configs/constant.php';
    require_once '../common/validate.php';
    require_once '../funcs/index_funcs.php';
    require_once '../funcs/auth_funcs.php';
    require_once '../funcs/send_mail_funcs.php';

    session_start();

    // Check isset session account
    if (!empty($_SESSION['account']['email'])) {
        header('location: ../index.php');
        exit();
    }

    // Check isser cookie email_shop
    if (!empty($_COOKIE['email_shop'])) {
        $_SESSION['account']['email'] = $_COOKIE['email_shop'];
        $_SESSION['account']['name'] = $_COOKIE['name_shop'];
        $_SESSION['success'] = 'Auto login';
        header('Location: ../index.php');
        exit();
    }

    // Get token
    $token = !empty($_GET['token']) ? trim($_GET['token']) : '';

    // Check token
    if (!empty($token)) {
        $checkToken = checkToken($connection, $token);

        if (!empty($checkToken)) {
            $userId = $checkToken['id'];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $errors = [];

                // Get form data
                $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
                $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';

                // Validate password
                if (!checkRequire($password)) {
                    $errors['password']['required'] = '<p>Please type password !</p>';
                } else {
                    if (!checkLength($password)) {
                        $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
                    }
                }

                // Validate repassword
                if (!checkRequire($repassword)) {
                    $errors['repassword'] = 'Please retype password !';
                } elseif (strlen($password) >= 6 && $password !== $repassword) {
                    $errors['password']['not_match'] = 'Password not match, please try again !';

                    $_SESSION['error'] = 'Password not match, please try again !';
                }

                // Reset password when not found any errors
                if (empty($errors)) {
                    $token = null;
                    resetPass($connection, $password, $token, $userId);

                    $_SESSION['success'] = 'Reset password success';

                    header('location: login.php');
                    exit();
                }
            }
        } else {
            $_SESSION['error'] = 'Not exist id';
            header('location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Link does not exist or expired';
        header('location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Reset password'; ?>

<?php require '../common/header_lib.php'; ?>

<body>
    <?php require '../common/navigation.php'; ?>

    <?php require '../common/branding.php'; ?>

    <?php require '../common/menu.php'; ?>

    <div class="maincontent-area">
        <div class="container">
            <div class="row">

                <div class="col-md-4">
                </div>

                <div class="col-md-4">
                    <form action="" method="post" id="form-login">
                        <h1 class="form-heading">Reset password</h1>
                        <div class="form-group">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-input" placeholder="Password" name="password" value="">
                        </div>

                        <!-- Message error password -->
                        <?php require_once '../lib/error_pass.php'; ?>

                        <div class="form-group">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-input" placeholder="Confirm password" name="repassword" value="">
                        </div>

                        <!-- Message error retype password -->
                        <?php require_once '../lib/error_repassword.php'; ?>

                        <button type="submit" class="form-submit">reset</button>
                        <div>
                            <br>
                            <a href="./register.php">Don't have an account? Register now</a>
                            <br><br>
                            <a href="./login.php">Already have an account? Login now</a>
                        </div>
                    </form>
                </div>

                <div class="col-md-4">
                </div>

            </div>
        </div>
    </div>
    <!-- End main content area -->

    <?php require '../common/footer_top.php'; ?>

    <?php require '../common/footer_bottom.php'; ?>

    <?php require '../common/footer_lib.php'; ?>
</body>

</html>