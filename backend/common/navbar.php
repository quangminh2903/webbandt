<?php
$currentUrl = getCurrentUrl();

$backDashboard = ($currentUrl == 'error.php' || $currentUrl == 'forgot_password.php' || $currentUrl == 'reset_password.php');

$userUrl = ($currentUrl == 'user_list.php' || $currentUrl == 'user_add.php' || $currentUrl == 'user_edit.php' || $currentUrl == 'user_list_restore.php');

$categoryUrl = ($currentUrl == 'category_list.php' || $currentUrl == 'category_add.php' || $currentUrl == 'category_edit.php' || $currentUrl == 'category_restore_list.php');

$brandUrl = ($currentUrl == 'brand_list.php' || $currentUrl == 'brand_add.php' || $currentUrl == 'brand_edit.php' || $currentUrl == 'brand_restore_list.php');

$bannerUrl = ($currentUrl == 'banner_list.php' || $currentUrl == 'banner_add.php' || $currentUrl == 'banner_edit.php' || $currentUrl == 'banner_restore_list.php');

$productUrl = ($currentUrl == 'product_list.php' || $currentUrl == 'product_add.php' || $currentUrl == 'product_edit.php' || $currentUrl == 'product_restore_list.php' || $currentUrl == 'product_detail.php');

$galleryUrl = ($currentUrl == 'gallery_list.php' || $currentUrl == 'gallery_add.php' || $currentUrl == 'gallery_edit.php' || $currentUrl == 'gallery_restore_list.php');

$orderUrl = ($currentUrl == 'order_list.php');

$orderItemUrl = ($currentUrl == 'order_items_list.php');
?>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <?php if ($currentUrl == 'index.php') { ?>
                <a class="brand" href="index.php">
                    Bootstrap Admin Template
                </a>

            <?php } else { ?>
                <a class="brand" href="../dashboard/index.php">
                    Bootstrap Admin Template
                </a>

            <?php } ?>
            <div class="nav-collapse">
                <?php if ($currentUrl == 'index.php' || $userUrl || $categoryUrl || $brandUrl || $bannerUrl || $currentUrl == 'customer_list.php' || $productUrl || $galleryUrl || $orderUrl || $orderItemUrl) { ?>
                    <ul class="nav pull-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user"></i>
                                <?php echo $_SESSION['username']; ?>
                                <b class="caret"></b>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="../auth/profile.php">Profile</a>
                                </li>

                                <li>
                                    <a href="../auth/change_password.php">Change password</a>
                                </li>

                                <li>
                                    <a href="../auth/logout.php">Logout</a>
                                </li>
                            </ul>

                        </li>

                    </ul>
                <?php } elseif ($backDashboard) { ?>
                    <ul class="nav pull-right">
                        <li class="">
                            <a href="../dashboard/index.php" class="">
                                <i class="icon-chevron-left"></i>
                                Back to Dashboard
                            </a>

                        </li>
                    </ul>
                <?php } elseif ($currentUrl == 'signup.php') { ?>
                    <ul class="nav pull-right">
                        <li class="">
                            <a href="login.php" class="">
                                Already have an account? Login now
                            </a>

                        </li>
                    </ul>
                <?php } elseif ($currentUrl == 'profile.php' || $currentUrl == 'change_password.php') { ?>
                    <ul class="nav pull-right">
                        <li class="">
                            <a href="../dashboard/index.php" class="">
                                <i class="icon-chevron-left"></i>
                                Back to Dashboard
                            </a>

                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-user"></i>
                                <?php echo $_SESSION['username']; ?>
                                <b class="caret"></b>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="../auth/profile.php">Profile</a>
                                </li>

                                <li>
                                    <a href="../auth/change_password.php">Change password</a>
                                </li>

                                <li>
                                    <a href="../auth/logout.php">Logout</a>
                                </li>

                            </ul>

                        </li>

                    </ul>
                <?php } ?>
            </div>
            <!--/.nav-collapse -->
        </div>
        <!-- /container -->
    </div>
    <!-- /navbar-inner -->
</div>
<!-- /navbar -->