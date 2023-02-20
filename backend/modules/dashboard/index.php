<?php
session_start();

if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<?php
$pageTitle = 'Dashboard';

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
                        <div class="widget">
                            <div class="widget-header"> <i class="icon-bookmark"></i>
                                <h3>Dashboard</h3>
                            </div>
                            <!-- /widget-header -->
                            <div class="widget-content">
                                <div class="shortcuts">
                                    <a href="../user/user_list.php" class="shortcut"><i class="shortcut-icon icon-user"></i><span class="shortcut-label">Users</span> </a>

                                    <a href="../category/category_list.php" class="shortcut"><i class="shortcut-icon icon-list-alt"></i><span class="shortcut-label">Categories</span> </a>

                                    <a href="../brand/brand_list.php" class="shortcut"><i class="shortcut-icon icon-flag"></i><span class="shortcut-label">Brands</span> </a>

                                    <a href="../banner/banner_list.php" class="shortcut"><i class="shortcut-icon icon-th-list"></i> <span class="shortcut-label">Banners</span> </a>

                                    <a href="../customer/customer_list.php" class="shortcut"> <i class="shortcut-icon icon-group"></i><span class="shortcut-label">Customers</span> </a>

                                    <a href="../product/product_list.php" class="shortcut"> <i class="shortcut-icon icon-book"></i><span class="shortcut-label">Products</span> </a>

                                    <a href="../orders/order_list.php" class="shortcut"><i class="shortcut-icon icon-shopping-cart"></i> <span class="shortcut-label">Orders</span> </a>
                                </div>
                                <!-- /shortcuts -->
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