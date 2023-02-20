<?php
$currentUrl = getCurrentUrl();

$userUrl = ($currentUrl == 'user_list.php' || $currentUrl == 'user_add.php' || $currentUrl == 'user_edit.php' || $currentUrl == 'user_list_backup.php');

$categoryUrl = ($currentUrl == 'category_list.php' || $currentUrl == 'category_add.php' || $currentUrl == 'category_edit.php' || $currentUrl == 'category_backup_list.php');

$brandUrl = ($currentUrl == 'brand_list.php' || $currentUrl == 'brand_add.php' || $currentUrl == 'brand_edit.php' || $currentUrl == 'brand_backup_list.php');

$bannerUrl = ($currentUrl == 'banner_list.php' || $currentUrl == 'banner_add.php' || $currentUrl == 'banner_edit.php' || $currentUrl == 'banner_backup_list.php');

$productUrl = ($currentUrl == 'product_list.php' || $currentUrl == 'product_add.php' || $currentUrl == 'product_edit.php' || $currentUrl == 'product_backup_list.php');

$galleryUrl = ($currentUrl == 'gallery_list.php' || $currentUrl == 'gallery_add.php' || $currentUrl == 'gallery_edit.php' || $currentUrl == 'gallery_backup_list.php');

$orderUrl = ($currentUrl == 'order_list.php');

$orderItemUrl = ($currentUrl == 'order_items_list.php');
?>

<div class="subnavbar">
    <div class="subnavbar-inner">
        <div class="container">
            <ul class="mainnav">
                <li <?php echo $currentUrl == 'index.php' ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'index.php' ? 'index.php' : '../dashboard/index.php'; ?>">
                        <i class="icon-bookmark"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li <?php echo $userUrl ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'user_list.php' ? './user_list.php' : '../user/user_list.php'; ?>">
                        <i class="icon-user"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li <?php echo $categoryUrl ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'category_list.php' ? 'category_list.php' : '../category/category_list.php'; ?>">
                        <i class="icon-list-alt"></i>
                        <span>Categories</span>
                    </a>
                </li>

                <li <?php echo $brandUrl ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'brand_list.php' ? 'brand_list.php' : '../brand/brand_list.php'; ?>">
                        <i class="icon-flag"></i>
                        <span>Brands</span>
                    </a>
                </li>

                <li <?php echo $bannerUrl ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'banner_list.php' ? 'banner_list.php' : '../banner/banner_list.php'; ?>">
                        <i class="icon-th-list"></i>
                        <span>Banners</span>
                    </a>
                </li>

                <li <?php echo $currentUrl == 'customer_list.php' ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'customer_list.php' ? 'customer_list.php' : '../customer/customer_list.php'; ?>">
                        <i class="icon-group"></i>
                        <span>Customers</span>
                    </a>
                </li>

                <li <?php echo $productUrl || $galleryUrl ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'product_list.php' ? 'product_list.php' : '../product/product_list.php'; ?>">
                        <i class="icon-book"></i>
                        <span>Products</span>
                    </a>
                </li>

                <li <?php echo $orderUrl ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $currentUrl == 'order_list.php' ? 'order_list.php' : '../orders/order_list.php'; ?>">
                        <i class="icon-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- /container -->
    </div>
    <!-- /subnavbar-inner -->
</div>
<!-- /subnavbar -->