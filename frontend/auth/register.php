<?php
    require_once '../../common/configs/connect.php';
    require '../../common/configs/constant.php';
    require_once '../common/validate.php';
    require_once '../funcs/index_funcs.php';
    require_once '../funcs/auth_funcs.php';

    session_start();

    // Check isset accound
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
        $name = !empty($_POST['name']) ? trim($_POST['name']) : '';
        $email = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $phone = !empty($_POST['phone']) ? trim($_POST['phone']) : '';
        $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
        $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';
        $field = !empty($_POST['fields']) ? trim($_POST['fields']) : '';

        // Get info email
        $checkEmail = checkInfo($connection, $email);

        // Validate customer's name
        if (!checkRequire($name)) {
            $errors['name']['required'] = 'Please type name !';
        }

        // Validate customer's phone
        if (!checkRequire($phone)) {
            $errors['phone']['required'] = 'Please type phone !';
        } else {
            if (!checkNumber($phone)) {
                $errors['phone']['not_type'] = 'Please type number phone';
            } else {
                if (!validateMobile($phone)) {
                    $errors['phone']['not_valid'] = 'Please type valid phone !';
                } else {
                    $checkPhone = getPhone($connection, $phone);

                    // Check exist phone
                    if (!empty($checkPhone)) {
                        $errors['phone']['exist'] = 'Exist phone';
                    }
                }
            }
        }

        // Validate customer's email
        if (!checkRequire($email)) {
            $errors['email']['required'] = 'Please type email !';
        } else {
            if (!checkValidEmail($email)) {
                $errors['email']['not_valid'] = 'Please type valid email address !';
            } else {
                if (!empty($checkEmail)) {
                    $errors['email']['not_exist'] = 'Exist Email';
                }
            }
        }

        // Validate customer's password
        if (!checkRequire($password)) {
            $errors['password']['required'] = '<p>Please type password !</p>';
        } else {
            if (!checkLength($password)) {
                $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
            }
        }

        // Validate customer's repassword
        if (!checkRequire($repassword)) {
            $errors['repassword'] = 'Please retype password !';
        } elseif (strlen($password) >= 6 && $password !== $repassword) {
            $errors['password']['not_match'] = 'Password not match, please try again !';
            $_SESSION['error'] = 'Password not match, please try again !';
        }

        // Validate customer's field
        if (empty($field)) {
            $errors['filed'] = 'Please read and confirm the terms';
        }

        // Register user when not found any errors
        if (empty($errors)) {
            if ($field == 'yes') {
                registerUser($name, $phone, $email, $password);

                $_SESSION['success'] = 'Register success';

                header('location: login.php');
                exit();
            }
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

<?php $pageTitle = 'Register'; ?>

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
                        <h1 class="form-heading">Register</h1>

                        <div class="form-group">
                            <i class="fa fa-user"></i>
                            <input type="text" class="form-input" placeholder="Name" name="name" value="<?php echo !empty($name) ? $name : ''; ?>">
                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message validate name  -->
                        <?php require_once '../lib/error_name.php'; ?>

                        <div class="form-group">
                            <i class="fa fa-phone"></i>
                            <input type="text" class="form-input" placeholder="Phone" name="phone" value="<?php echo !empty($phone) ? $phone : ''; ?>">
                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message validate error phone  -->
                        <?php require_once '../lib/error_phone.php'; ?>

                        <div class="form-group">
                            <i class="fa fa-envelope"></i>
                            <input type="text" class="form-input" placeholder="Email" name="email" value="<?php echo !empty($email) ? $email : ''; ?>">
                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message validate error email  -->
                        <?php require_once '../lib/error_email.php'; ?>

                        <div class="form-group">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-input" placeholder="Password" name="password" value="">
                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message validate error pass  -->
                        <?php require_once '../lib/error_pass.php'; ?>

                        <div class="form-group">
                            <i class="fa fa-key"></i>
                            <input type="password" class="form-input" placeholder="Confirm password" name="repassword" value="">
                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                        </div>

                        <!-- Message validate error pass  -->
                        <?php require_once '../lib/error_repassword.php'; ?>

                        <div class="form-group">
                            <label class="inline" for="rememberme">
                                <input id="Field" name="fields" type="checkbox" class="field login-checkbox" value="yes" tabindex="4" <?php echo !empty($field) ? 'checked="checked"' : '' ?> />
                                &emsp; Agree with the Terms & Conditions.
                                &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>
                            </label>
                        </div>

                        <!-- Message error field -->
                        <?php require_once '../lib/error_field.php'; ?>

                        <button type="submit" class="form-submit">register</button>

                        <div>
                            <br>
                            <a href="login.php">Already have an account? Login now</a>
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