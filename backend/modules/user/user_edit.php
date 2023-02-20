<?php
require_once '../../../common/configs/connect.php';
require_once '../../funcs/user_funcs.php';
require_once '../../funcs/auth_funcs.php';
require_once '../../common/validate.php';

session_start();

// Check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check isset user's id
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: user_list.php');
    exit();
}
$id = !empty($_GET['id']) ? trim($_GET['id']) : '';

// Get info user by user's id and users's active
$infoUser = getInfoUserEdit($connection, $id, $activeDelete);

// Check isset infoUser
if (empty($infoUser)) {
    header('location: user_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    // Get form data
    $activeUser = !empty($_POST['active']) ? trim($_POST['active']) : '';
    $password = !empty($_POST['password']) ? trim($_POST['password']) : '';
    $repassword = !empty($_POST['repassword']) ? trim($_POST['repassword']) : '';

    if (!checkRequire($password)) {
        if ($repassword != '') {
            $errors['password']['required'] = 'Please retype password !';
        }

        // update user with out pass when not found any error
        if (empty($errors)) {
            $data = [
                $activeUser,
                $id
            ];

            updateUserWithOutPass($connection, $data);

            $_SESSION['success'] = 'You change username <b>' . $infoUser['username'] . '</b> successfully';

            header('location: user_list.php');
            exit();
        }
    } elseif ($password !== '') {
        // validate password
        if (!checkLength($password)) {
            $errors['password']['min_length'] = 'Please type password equal or more 6 characters';
        }

        // validate retype password
        if (!checkRequire($repassword)) {
            $errors['repassword']['required'] = 'Please retype repassword !';
        } elseif ($password !== $repassword) {
            $errors['password']['not_match'] = 'Password not match, please try again !';
            $_SESSION['error'] = 'Password not match, please try again !';
        }

        // update user when not found any error
        if (empty($errors)) {
            $passBcrypt = password_hash($password, PASSWORD_BCRYPT);

            $dataUpdate = [
                $passBcrypt,
                $activeUser,
                $id
            ];

            updateUser($connection, $dataUpdate);

            $_SESSION['success'] = 'You change username <b>' . $infoUser['username'] . '</b> successfully';

            header('location: user_list.php');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Edit Users';

require '../../common/header_lib.php';
?>

<?php require '../../../common/configs/constant.php'; ?>

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
                                <h3>Edit user <b style="color:#F30"><?php echo $infoUser['username']; ?></b></h3>
                            </div>
                            <!-- /widget-header -->

                            <div class="widget-content">
                                <form id="edit-profile" class="form-horizontal" action="" method="post">
                                    <fieldset>
                                        <br>
                                        <div class="control-group">
                                            <label class="control-label" for="username">Username</label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="username" name="username" value="<?php echo $infoUser['username']; ?>" readonly>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="email">Email</label>
                                            <div class="controls">
                                                <input type="text" class="span4" id="email" name="email" value="<?php echo $infoUser['email']; ?>" readonly>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="active">Active</label>
                                            <div class="controls">
                                                <select name="active">
                                                    <option <?php echo ($infoUser['active'] == '0') ? 'selected=\"selected\"' : '';  ?> value="0" class="text-center">INACTIVE</option>
                                                    <option <?php echo ($infoUser['active'] == '1') ? 'selected=\"selected\"' : '';  ?> value="1" class="text-center">ACTIVE</option>
                                                </select>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <div class="control-group">
                                            <label class="control-label" for="password1">Password</label>
                                            <div class="controls">
                                                <input type="password" name="password" class="span4" id="password1">
                                                <?php
                                                echo !empty($errors['password']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['password']['required'] . '</em>' : '';
                                                echo !empty($errors['password']['min_length']) ? '<br><em class="error-notice">&nbsp;' . $errors['password']['min_length'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->


                                        <div class="control-group">
                                            <label class="control-label" for="password2">Confirm</label>
                                            <div class="controls">
                                                <input type="password" class="span4" id="password2" name="repassword">
                                                <?php
                                                echo !empty($errors['repassword']['required']) ? '<br><em class="error-notice">&nbsp;' . $errors['repassword']['required'] . '</em>' : '';
                                                ?>
                                            </div> <!-- /controls -->
                                        </div> <!-- /control-group -->

                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <div class="form-actions">
                                            <button type="submit" class="btn btn-primary">Save</button>
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