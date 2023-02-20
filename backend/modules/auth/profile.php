<?php
    require_once '../../../common/configs/connect.php';
    require_once '../../funcs/auth_funcs.php';

    session_start();

    // Check isser session username
    if (empty($_SESSION['username'])) {
        header('location: ../auth/login.php');
        exit();
    }

    $username = $_SESSION['username'];

    // Get profile user by user's username
    $profileUser = getUserProfile($connection, $username);
?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Profile';

require '../../common/header_lib.php';
?>

<?php require '../../../common/configs/constant.php'; ?>

<body>
    <?php require '../../common/navbar.php'; ?>

    <?php require '../../common/menu.php'; ?>

    <div class="account-container register">

        <div class="content clearfix">

            <form action="" method="post">

                <h1>Profile</h1>

                <div class="login-fields">

                    <p>Profile account:</p>

                    <div class="field">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo $profileUser['username']; ?>" placeholder="Username" class="login" readonly />
                    </div> <!-- /field -->

                    <div class="field">
                        <label for="email">Email Address:</label>
                        <input type="text" id="email" name="email" value="<?php echo $profileUser['email']; ?>" placeholder="Email" class="login" readonly />
                    </div> <!-- /field -->

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