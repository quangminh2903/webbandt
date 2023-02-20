<?php
    require_once '../../../common/configs/connect.php';
    require_once '../../funcs/auth_funcs.php';
    require_once '../../funcs/send_mail_funcs.php';
    require_once '../../common/validate.php';

    session_start();

    // Check isset session username
    if (!empty($_SESSION['username'])) {
        header('location: ../dashboard/index.php');
        exit();
    }

    // Check isset cookie username
    if (!empty($_COOKIE['username'])) {
        $_SESSION['username'] = $_COOKIE['username'];
        $_SESSION['success'] = 'Auto Login';

        header('Location: index.php');
        exit();
    }

    // Get data token
    $token = !empty($_GET['token']) ? trim($_GET['token']) : '';

    if (!empty($token)) {
        // Check token
        $checkToken = checkToken($connection, $token);

        // Checkdata
        if (!empty($checkToken)) {
            $userId = $checkToken['id'];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $errors = [];

                // Get data form
                $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
                $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';

                // Validate password
                if (!checkRequire($password)) {
                    $errors['password']['required'] = 'Please type password !';
                } else {
                    if (!checkLength($password)) {
                        $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
                    }
                }

                // Validate repassword
                if (!checkRequire($repassword)) {
                    $errors['repassword'] = 'Please retype password !';
                } elseif ($password !== $repassword) {
                    $errors['password']['not_match'] = 'Password not match, please try again !';
                }

                // reset password when not found any error
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
<html lang="en">

<?php
$pageTitle = 'Reset password';

require '../../common/header_lib.php';
?>

<?php require '../../../common/configs/constant.php'; ?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <div class="account-container register">

        <div class="content clearfix">

            <form action="" method="post">

                <h1>Reset password</h1>

                <div class="login-fields">

                    <p>Reset password account:</p>

                    <div class="field">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" value="" placeholder="Password" class="login" />

                        <!-- Message error password -->
                        <?php require_once '../../lib/error_password.php' ?>
                    </div> <!-- /field -->

                    <div class="field">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="repassword" value="" placeholder="Confirm Password" class="login" />

                        <!-- Message error repassword -->
                        <?php require_once '../../lib/error_repass.php'; ?>
                    </div> <!-- /field -->

                </div> <!-- /login-fields -->

                <div class="login-actions">
                    <button class="button btn btn-primary btn-large">Reset</button>

                </div> <!-- .actions -->

            </form>

        </div> <!-- /content -->

    </div> <!-- /account-container -->

    <div class="login-extra">
        Already have an account? <a href="login.php">Login to your account</a>
    </div> <!-- /login-extra -->

    <?php require_once '../../common/footer_lib.php'; ?>

</body>

</html>