<?php
    require_once '../../common/configs/connect.php';
    require '../../common/configs/constant.php';
    require_once '../common/validate.php';
    require_once '../funcs/index_funcs.php';
    require_once '../funcs/auth_funcs.php';

    session_start();

    // Check isset session account
    if (!empty($_SESSION['account']['email'])) {
        header('location: ../index.php');
        exit();
    }

    // Check isset cookie email_shop
    if (!empty($_COOKIE['email_shop'])) {
        $_SESSION['account']['email'] = $_COOKIE['email_shop'];
        $_SESSION['account']['name'] = $_COOKIE['name_shop'];

        $_SESSION['success'] = 'Auto login';
        header('Location: ../index.php');
        exit();
    }


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];

        // Get data form
        $email = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $password = !empty($_POST['password']) ? trim($_POST['password']) : '';

        // Get info email
        $checkEmail = checkInfo($connection, $email);

        // Validate customer's email
        if (!checkRequire($email)) {
            $errors['email']['required'] = 'Please type email !';
        } else {
            if (!checkValidEmail($email)) {
                $errors['email']['not_valid'] = 'Please type valid email address !';
            } else {
                // Check exist email
                if (empty($checkEmail)) {
                    $errors['email']['not_exist'] = 'Not exist Email';
                } else {
                    $checActive = $checkEmail->active;

                    if ($checActive == '0') {
                        $errors['email']['inactive'] = 'Account inactive';
                    } elseif ($checActive == '2') {
                        $errors['email']['delete'] = 'Account has been locked';
                    } elseif ($checActive == '3') {
                        $errors['email']['ban'] = 'Account has been ban';
                    }
                }
            }
        }

        // Validate customer's password
        if (!checkRequire($password)) {
            $errors['password']['required'] = '<p>Please type password !</p>';
        }

        if ($email !== '' && $password !== '') {
            $checkInfo = getUserInfo($connection, $email, $active);

            // Check email
            if (empty($checkInfo)) {
                $errors['wrong'] = 'Wrong password or email !';
            } else {
                $passInfo = $checkInfo->password;
                $checkPass = password_verify($password, $passInfo);

                // Check password
                if (!$checkPass) {
                    $errors['wrong'] = 'Wrong password or email !';

                    $_SESSION['error'] = 'Wrong password or email !';
                }
            }
        }

        // Login when not found any errors
        if (empty($errors)) {
            $_SESSION['account']['email'] = $email;

            $checkName = $checkEmail->name;
            $_SESSION['account']['name'] = $checkName;

            if (!empty($remember)) {
                setcookie('email_shop', $email, time() + 86400);
                setcookie('name_shop', $checkName, time() + 86400);
                setcookie('password_shop', $password, time() + 86400);
            }

            $_SESSION['success'] = 'Login success';

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

<?php $pageTitle = 'Login'; ?>

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
                        <h1 class="form-heading">Login</h1>
                        <div class="form-group">
                            <i class="fa fa-envelope"></i>
                            <input type="text" class="form-input" placeholder="Email" name="email" value="<?php echo !empty($email) ? $email : ''; ?>">
                            &nbsp;
                            &nbsp;
                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message error email -->
                        <?php require_once '../lib/error_email.php'; ?>

                        <div class="form-group">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-input" placeholder="Password" name="password" value="">
                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message error password -->
                        <?php require_once '../lib/error_pass.php'; ?>

                        <div class="form-group">
                            <label class="inline" for="rememberme">
                                <input id="Field" name="remember" type="checkbox" class="field login-checkbox" value="1" tabindex="4" <?php echo !empty($remember) ? 'checked="checked"' : '' ?> />
                                &emsp; Remember me
                            </label>
                        </div>

                        <button type="submit" class="form-submit">login</button>

                        <div>
                            <br>

                            <a href="./register.php">Don't have an account? Register now</a>

                            <br><br>

                            <a href="./forgot_password.php">Lost your password ?</a>
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