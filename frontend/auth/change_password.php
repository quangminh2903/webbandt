<?php
    require_once '../../common/configs/connect.php';
    require '../../common/configs/constant.php';
    require_once '../common/validate.php';
    require_once '../funcs/index_funcs.php';
    require_once '../funcs/auth_funcs.php';

    session_start();

    // Check isset session account
    if (empty($_SESSION['account']['email'])) {
        header('location: login.php');
        exit();
    }

    $email = $_SESSION['account']['email'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];

        // Get data form
        $currentPass = !empty($_POST['current_password']) ? trim($_POST['current_password']) : '';
        $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
        $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';

        // Get info user
        $profileUser = getUserProfile($connection, $email);

        // Validate customer's current pasword
        if (!checkRequire($currentPass)) {
            $errors['current_password']['required'] = 'Please type current password !';
        } else {
            $passUser = $profileUser['password'];
            $checkPass = password_verify($currentPass, $passUser);

            // Check password
            if (!$checkPass) {
                $errors['current_password']['wrong'] = 'Wrong password !';
            }
        }

        // Validate customer's password
        if (!checkRequire($password)) {
            $errors['password']['required'] = 'Please type password !';
        } else {
            if (!checkLength($password)) {
                $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
            }
        }

        // Validate customer's repassword
        if (!checkRequire($repassword)) {
            $errors['repassword'] = 'Please retype password !';
        } elseif ($password !== $repassword) {
            $errors['password']['not_match'] = 'Password not match, please try again !';
        }

        // Change password when not found any errors
        if (empty($errors)) {
            $userId = $profileUser['id'];

            changePassword($connection, $password, $userId);

            $_SESSION['success'] = 'Change password success';

            header('location: ../index.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Change password'; ?>

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
                        <h1 class="form-heading">Change password</h1>

                        <div class="form-group">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-input" placeholder="Current Password" name="current_password" value="">
                        </div>

                        <!-- Message error current password -->
                        <?php require_once '../lib/error_current_password.php'; ?>

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

                        <!-- Message error current retype password -->
                        <?php require_once '../lib/error_repassword.php'; ?>

                        <button type="submit" class="form-submit">change password</button>

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