<?php
require_once '../common/configs/connect.php';
require '../common/configs/constant.php';
require_once './funcs/single_product_funcs.php';
require_once './funcs/cart_funcs.php';

session_start();

// Get list id reviewed recently
if (array_key_exists('viewed_products', $_COOKIE)) {
    $cookie = $_COOKIE['viewed_products'];
    $cookieId = unserialize($cookie);
}

if (!empty($_SESSION['account']['email'])) {
    $email = $_SESSION['account']['email'];
}

// Pagination
$listOrdersByEmail = getAllOrdersByEmail($connection, $email);

$perPage = 15;

$allNum = count($listOrdersByEmail);
$maxPage = ceil($allNum / $perPage);

if (!empty($_GET['page'])) {
    $page = trim($_GET['page']);
} else {
    $page = 1;
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
}

$offset = ($page - 1) * $perPage;

// Get all list orders
$listAllOrders = getAllOrdersPagintaionByEmail($connection, $email, $offset, $perPage);
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'My Cart'; ?>
<?php require './common/header_lib.php'; ?>

<body>
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>

    <?php require './common/navigation.php'; ?>

    <?php require './common/branding.php'; ?>

    <?php require './common/menu.php'; ?>

    <?php require './common/slider.php'; ?>

    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <?php require './common/sidebar.php'; ?>
                </div>

                <div class="col-md-8">
                    <div class="product-content-right">
                        <div class="woocommerce">
                            <form method="post" action="">
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                        <tr>
                                            <th class="product-thumbnail">Orderer</th>
                                            <th class="product-name">Phone</th>
                                            <th class="product-name">Money</th>
                                            <th class="product-name">Products</th>
                                            <th class="product-price">Status</th>
                                            <th class="product-quantity">Pay</th>
                                            <th class="product-subtotal"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($listAllOrders) > 0) { ?>
                                            <h1 style="text-align: center;">History Order</h1>
                                            <br>
                                            <?php
                                            foreach ($listAllOrders as $order) {
                                                if ($order['status'] == 0) {
                                                    $status = '<div style="color:blue;"><i class="fa fa-retweet"></i>  Processing</div>';
                                                } elseif ($order['status'] == 2) {
                                                    $status = '<div style="color:red;">X  Cancel</div>';
                                                } else {
                                                    $status = '<div style="color: #0bb827""><i  class="fa fa-check"></i>  Completed</div>';
                                                }
                                            ?>
                                                <tr class="cart_item">
                                                    <td class="product-remove">
                                                        <?php echo $order['customer_name']; ?>
                                                    </td>

                                                    <td class="product-thumbnail">
                                                        <?php echo $order['customer_phone']; ?>
                                                    </td>

                                                    <td class="product-name">
                                                        $<?php echo number_format((float)$order['total_money'], 2, '.', ''); ?>
                                                    </td>

                                                    <td class="product-price">
                                                        <?php echo $order['total_products']; ?>
                                                    </td>

                                                    <td class="product-price">
                                                        <?php echo $status ?>
                                                    </td>
                                                    <td class="product-price">
                                                        Ship cod
                                                    </td>
                                                    <td class="product-price">
                                                        <a title="Information order id <?php echo $order['id']; ?>" href="./cart_order_detail.php?order-id=<?php echo $order['id']; ?>" class="button"><i class="fa fa-info" style="font-weight: bold;"></i></a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else {
                                            ?>
                                            <tr>
                                                <td class="actions" colspan="7">
                                                    <b>Shopping cart order is empty<p>
                                                </td>
                                            </tr>
                                        <?php

                                        } ?>

                                    </tbody>
                                </table>
                            </form>


                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="product-pagination text-center">
                                <nav>
                                    <ul class="pagination">
                                        <?php
                                        if ($page > 1) {
                                            $prevPage = $page - 1;

                                            echo '<li class="page-item">
                                    <a href="cart_orders_customer.php?page=' . $prevPage . '" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                            </li>';
                                        }

                                        for ($index = 1; $index <= $maxPage; $index++) {
                                            if ($maxPage == 1 && $page = 1) {
                                                echo '';
                                            } else { ?>
                                                <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>"><a href="<?php echo 'cart_orders_customer.php?page=' . $index; ?>">
                                                        <?php echo $index; ?>
                                                    </a></li>
                                        <?php }
                                        }

                                        $nextPage = $page + 1;
                                        if ($page < $maxPage) {
                                            echo '<li class="page-item">
                                    <a href="cart_orders_customer.php?page=' . $nextPage . '" aria-label="Previous">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                            </li>';
                                        }
                                        ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require './common/footer_top.php'; ?>

    <?php require './common/footer_bottom.php'; ?>

    <?php require './common/footer_lib.php'; ?>


</body>

</html>