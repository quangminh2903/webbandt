<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../funcs/auth_funcs.php';
    require_once '../../common/validate.php';

    // Check isset session username
    if (empty($_SESSION['username'])) {
        header('location: ../auth/login.php');
        exit();
    }

    $username = $_SESSION['username'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];

        // Get data form
        $currentPass = !empty($_POST['current_password']) ? trim($_POST['current_password']) : '';
        $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
        $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';

        $profileUser = getUserProfile($connection, $username);

        // Validate currentpass
        if (!checkRequire($currentPass)) {
            $errors['current_password']['required'] = 'Please type current password !';
        } else {
            $passUser = $profileUser['password'];

            // Check password
            $checkPass = password_verify($currentPass, $passUser);

            if (!$checkPass) {
                $errors['current_password']['wrong'] = 'Wrong password !';
            }
        }

        // Validate password
        if (!checkRequire($password)) {
            $errors['password']['required'] = 'Please type password !';
        } else {
            if (!checkLength($password)) {
                $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
            }
        }

        // validate repassword
        if (!checkRequire($repassword)) {
            $errors['repassword'] = 'Please retype password !';
        } elseif ($password !== $repassword) {
            $errors['password']['not_match'] = 'Password not match, please try again !';
        }

        // change password when not found any error
        if (empty($errors)) {
            $userId = $profileUser['id'];

            changePassword($connection, $password, $userId);

            $_SESSION['success'] = 'Change password success';

            header('location: ../dashboard/index.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Change Password';

require '../../common/header_lib.php';
?>

<?php require '../../../common/configs/constant.php'; ?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <?php require '../../common/menu.php'; ?>

    <div class="account-container register">

        <div class="content clearfix">

            <form action="" method="post">

                <h1>Change Password</h1>

                <div class="login-fields">

                    <p>Change password account:</p>

                    <div class="field">
                        <label for="password">Current Password:</label>
                        <input type="password" id="password" name="current_password" value="" placeholder="Current Password" class="login" />

                        <!-- Message error repassword -->
                        <?php require_once '../../lib/error_current_password.php'; ?>
                    </div> <!-- /field -->

                    <div class="field">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password" value="" placeholder="New Password" class="login" />

                        <!-- Message error password -->
                        <?php require_once '../../lib/error_password.php'; ?>
                    </div> <!-- /field -->

                    <div class="field">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="repassword" value="" placeholder="Confirm Password" class="login" />

                        <!-- Message error repassword -->
                        <?php require_once '../../lib/error_repass.php'; ?>
                    </div> <!-- /field -->

                    <div class="login-actions">
                        <button class="button btn btn-primary btn-large">Reset</button>

                    </div> <!-- .actions -->

                </div> <!-- /login-fields -->
            </form>

        </div> <!-- /content -->

    </div> <!-- /account-container -->
    <br><br>

    <?php require '../../common/footer_top.php'; ?>

    <?php require '../../common/footer_bottom.php'; ?>

    <?php require '../../common/footer_lib.php'; ?>
</body>

</html>