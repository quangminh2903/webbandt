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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];

        // Get data form
        $email = !empty($_POST['email']) ? trim($_POST['email']) : '';

        // Check email
        $checkEmail = getEmail($connection, $email);

        // Validate email
        if (!checkRequire($email)) {
            $_SESSION['error'] = 'Please type email';
            $errors = 'Please type email.';
        } else {
            if (!checkValidEmail($email)) {
                $_SESSION['error'] = 'Please type valid email address';
                $errors = 'Please type valid email address';
            } else {
                if (empty($checkEmail)) {
                    $_SESSION['error'] = 'Not exist Email';
                    $errors = 'Not exist Email';
                } else {
                    $checActive = $checkEmail['active'];

                    if ($checActive == '0') {
                        $errors = 'Account inactive';
                        $_SESSION['error'] = 'Account inactive';
                    } elseif ($checActive == '2') {
                        $errors = 'Account has been locked';
                        $_SESSION['error'] = 'Account has been locked';
                    }
                }
            }
        }

        // Send email reset password when not found any error
        if (empty($errors)) {
            $userId = $checkEmail['id'];
            $token = sha1(uniqid() . time());

            $data = [
                $token,
                $userId
            ];

            getToken($connection, $data);

            $fullUrl = $_SERVER['HTTP_REFERER'];
            $arrUrl = explode('backend', $fullUrl);
            $linkReset = $arrUrl[0] . 'backend/modules/auth/reset_password.php?token=' . $token;


            $to = $email;
            $subject = 'Request password reset';

            $content = 'Hello ' . $email . '<br/><br/>';
            $content .= 'We just received a request to retrieve the password on your account. To reset your password please click on the link below: <br/><br/>';
            $content .= $linkReset . '<br/><br/>';
            $content .= 'If not your request, ignore this email.';

            sendMail($to, $subject, $content);

            $_SESSION['success'] = 'Email sent successfully, Please check your email.';

            header('location: login.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Forgot Password';

require '../../common/header_lib.php';
?>

<?php require '../../../common/configs/constant.php'; ?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <div class="account-container register">

        <div class="content clearfix">

            <form action="" method="post">

                <h1>Forgot Account</h1>

                <div class="login-fields">

                    <p>Forgot account:</p>

                    <div class="field">
                        <label for="email">Email Address:</label>
                        <input type="text" id="email" name="email" value="<?php echo !empty($email) ? $email : ''; ?>" placeholder="Email" class="login" />
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