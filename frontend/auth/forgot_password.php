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

        // Get info email
        $checkEmail = checkInfo($connection, $email);

        // Validate customer's email
        if (!checkRequire($email)) {
            $errors['email']['required'] = 'Please type email !';

            $_SESSION['error'] = 'Please type email';
        } else {
            if (!checkValidEmail($email)) {
                $errors['email']['not_valid'] = 'Please type valid email address !';

                $_SESSION['error'] = 'Please type valid email address !';
            } else {
                if (empty($checkEmail)) {
                    $errors['email']['not_exist'] = 'Not exist Email';

                    $_SESSION['error'] = 'Not exist Email';
                } else {
                    $checActive = $checkEmail->active;

                    // Check active email
                    if ($checActive == '0') {
                        $errors['email']['inactive'] = 'Account inactive';
                        $_SESSION['error'] = 'Account inactive';
                    } elseif ($checActive == '2') {
                        $errors['email']['delete'] = 'Account has been locked';
                        $_SESSION['error'] = 'Account has been locked';
                    } elseif ($checActive == '3') {
                        $errors['email']['ban'] = 'Account has been ban';
                        $_SESSION['error'] = 'Account has been ban';
                    }
                }
            }
        }

        // Forgot password when not found any errors
        if (empty($errors)) {
            $checkMail = getEmail($connection, $email);
            $userId = $checkMail['id'];
            $token = sha1(uniqid() . time());

            // Get token by id
            $data = [
                $token,
                $userId
            ];
            getToken($connection, $data);

            // Get url
            $fullUrl = $_SERVER['HTTP_REFERER'];
            $arrUrl = explode('frontend', $fullUrl);
            $linkReset = $arrUrl[0] . 'frontend/auth/reset_password.php?token=' . $token;


            $to = $email;
            $subject = 'Request password reset';

            $content = 'Hello ' . $email . '<br/><br/>';
            $content .= 'We just received a request to retrieve the password on your account. To reset your password please click on the link below: <br/><br/>';
            $content .= $linkReset . '<br/><br/>';
            $content .= 'If not your request, ignore this email.';

            echo sendMail($to, $subject, $content);

            $_SESSION['success'] = 'Email sent successfully, Please check your email.';

            header('location: login.php');
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

<?php $pageTitle = 'Forgot password'; ?>

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
                        <h1 class="form-heading">Forgot password</h1>

                        <div class="form-group">
                            <i class="fa fa-envelope"></i>

                            <input type="text" class="form-input" placeholder="Email" name="email" value="<?php echo !empty($email) ? $email : ''; ?>">

                            &nbsp;
                            &nbsp;

                            <span class="required" style="color: red; margin-top: 10px;"><b>*</b></span>

                        </div>

                        <button type="submit" class="form-submit">reset</button>

                        <div>
                            <br>
                            <a href="register.php">Don't have an account? Register now</a>

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