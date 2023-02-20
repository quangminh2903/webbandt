<?php
    $currentUrl = getCurrentUrl();

    $authUrl = ($currentUrl == 'login.php' || $currentUrl == 'register.php' || $currentUrl == 'forgot_password.php' || $currentUrl == 'reset_password.php' || $currentUrl == 'change_password.php' || $currentUrl == 'profile.php');
?>

<div class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="user-menu">
                    <ul>
                        <?php
                        if (!empty($_SESSION['account']['email'])) {
                            if ($authUrl) {
                                echo '<li><a href="../cart_orders_customer.php"><i class="fa fa-user"></i> My Orders</a></li>';
                            } else {
                                echo '<li><a href="cart_orders_customer.php"><i class="fa fa-user"></i> My Orders</a></li>';
                            }
                        } else {
                            if ($authUrl) {
                                echo '<li><a href="./login.php"><i class="fa fa-user"></i> Login</a></li>';
                                echo '<li><a href="./register.php"><i class="fa fa-user"></i> Register</a></li>';
                            } else {
                                echo '<li><a href="./auth/login.php"><i class="fa fa-user"></i> Login</a></li>';
                                echo '<li><a href="./auth/register.php"><i class="fa fa-user"></i> Register</a></li>';
                            }
                        }
                        ?>

                    </ul>
                </div>
            </div>

            <div class="col-md-7">
                <div class="header-right">
                    <ul class="list-unstyled list-inline">

                        <?php if (!empty($_SESSION['account']['email'])) { ?>
                            <li class="dropdown dropdown-small">
                                <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="#">
                                    <span class="key"><i class="fa fa-user"></i> </span>
                                    <span class="value"> <?php echo $_SESSION['account']['name']; ?> </span><b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <?php if ($currentUrl == 'change_password.php' || $currentUrl == 'profile.php') { ?>
                                        <li><a href="./profile.php">Profile</a></li>
                                        <li><a href="./change_password.php">Change password</a></li>
                                        <li><a href="./logout.php">Logout</a></li>
                                    <?php } else { ?>
                                        <li><a href="./auth/profile.php">Profile</a></li>
                                        <li><a href="./auth/change_password.php">Change password</a></li>
                                        <li><a href="./auth/logout.php">Logout</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End header area -->