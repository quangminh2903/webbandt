<?php
    require_once '../../common/configs/connect.php';
    require '../../common/configs/constant.php';
    require_once '../common/validate.php';
    require_once '../funcs/index_funcs.php';
    require_once '../funcs/auth_funcs.php';

    session_start();

    // Check isset session acccount
    if (empty($_SESSION['account']['email'])) {
        header('location: login.php');
        exit();
    }

    $email = $_SESSION['account']['email'];

    // Get info email
    $profileUser = getUserProfile($connection, $email);
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Profile'; ?>

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
                        <h1 class="form-heading">Profile</h1>

                        <div class="form-group">
                            <i class="fa fa-user"></i>
                            <input type="text" class="form-input" placeholder="Name" name="name" value="<?php echo $profileUser['name']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <i class="fa fa-phone"></i>
                            <input type="text" class="form-input" placeholder="Phone" name="phone" value="<?php echo $profileUser['phone']; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <i class="fa fa-envelope"></i>
                            <input type="text" class="form-input" placeholder="Email" name="email" value="<?php echo $profileUser['email']; ?>">
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