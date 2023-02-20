<?php
require_once '../../../common/configs/connect.php';
require '../../../common/configs/constant.php';
require_once '../../funcs/user_funcs.php';
require_once '../../funcs/auth_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check empty session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $username = !empty($_POST['username']) ? trim($_POST['username']) : '';
    $email = !empty($_POST['email']) ? trim($_POST['email']) : '';
    $activeUser = !empty($_POST['active']) ? trim($_POST['active']) : '';
    $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
    $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';

    // validate user's username
    if (!checkRequire($username)) {
        $errors['username']['required'] = 'Please type username !';
    } else {
        if (!checkLength($username)) {
            $errors['username']['min_length'] = 'Please type username equal or more 6 characters !';
        } else {
            $checkUsername = getUser($connection, $username);

            if (!empty($checkUsername)) {
                $errors['username']['exist'] = 'Exist Username';
            }
        }
    }

    // validate user's email
    if (!checkRequire($email)) {
        $errors['email']['required'] = 'Please type email !';
    } else {
        if (!checkValidEmail($email)) {
            $errors['email']['not_valid'] = 'Please type valid email address !';
        } else {
            $checkEmail = getEmail($connection, $email);

            if (!empty($checkEmail)) {
                $errors['email']['exist'] = 'Exist Email';
            }
        }
    }

    // validate user's password
    if (!checkRequire($password)) {
        $errors['password']['required'] = 'Please type password !';
    } else {
        if (!checkLength($password)) {
            $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
        }
    }

    // validate user's repassword
    if (!checkRequire($repassword)) {
        $errors['repassword'] = 'Please retype password !';
    } elseif ($password !== $repassword) {
        $errors['password']['not_match'] = 'Password not match, please try again !';
    }

    // create new user when not found errors
    if (empty($errors)) {
        $passBcrypt = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            $username,
            $email,
            $passBcrypt,
            $activeUser
        ];

        createUser($connection, $data);

        $_SESSION['success'] = 'Add user success';

        header('location: user_list.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Add new user';

require '../../common/header_lib.php';
?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <?php require '../../common/menu.php'; ?>

    <div class="main">
        <div class="main-inner">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <div class="widget widget-table action-table">
                            <div class="widget-header"> <i class="icon-user"></i>
                                <h3>Add new user</h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="username">Username <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="username" name="username" value="<?php echo !empty($username) ? $username : ''; ?>">

                                                <!-- Message username -->
                                                <?php require '../../lib/error_user_username.php'; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="email">Email <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="email" name="email" value="<?php echo !empty($email) ? $email : ''; ?>">

                                                <!-- Message password -->
                                                <?php require '../../lib/error_user_pass.php'; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active" class="span4">
                                                    <option value="0" class="text-center">Inactive</option>
                                                    <option <?php echo (!empty($activeUser) && $activeUser == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">Active</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="password1">Password <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="password" name="password" class="span4" id="password1">

                                                <!-- Message user password -->
                                                <?php require '../../lib/error_user_pass.php'; ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->


                                        <div class="control-group">
                                            <label class="control-label" for="password2">Confirm <span class="error-notice">*</span></label>
                                            <div class="controls">
                                                <input type="password" class="span4" id="password2" name="repassword">
                                                <?php
                                                echo !empty($errors['repassword']) ? '<br><em class="error-notice">&nbsp;' . $errors['repassword'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                            &ensp;
                                            <a class="btn" href="./user_list.php">Cancel</a>
                                        </div> <!-- /form-actions -->
                                    </fieldset>
                                </form>

                            </div>
                            <!-- /widget-content -->
                        </div>
                    </div>
                    <!-- /span6 -->
                </div>
                <!-- /row -->

            </div>
            <!-- /container -->
        </div>
        <!-- /main-inner -->
    </div>
    <!-- /main -->

    <?php require '../../common/footer_top.php'; ?>

    <?php require '../../common/footer_bottom.php'; ?>

    <?php require '../../common/footer_lib.php'; ?>
</body>

</html>