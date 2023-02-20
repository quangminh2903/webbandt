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

// Check order id
if (empty($_GET['order-id']) || !is_numeric($_GET['order-id'])) {
    $_SESSION['error'] = 'Not exist order id';

    header('location: index.php');
    exit();
}

$orderId = !empty($_GET['order-id']) ? trim($_GET['order-id']) : '';

$inforOrderItems = getInfoOrder($connection, $orderId);

if (empty($inforOrderItems)) {
    $_SESSION['error'] = 'Not exist order id';

    header('location: index.php');
    exit();
}

if (!empty($_POST['my-cart'])) {
    header('location: cart_orders_customer.php');
    exit();
}
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Order detail'; ?>
<?php require './common/header_lib.php'; ?>

<body>
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
                                            <th class="product-thumbnail">Product</th>
                                            <th class="product-name">Image</th>
                                            <th class="product-name">Price</th>
                                            <th class="product-name">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($inforOrderItems) > 0) {
                                        ?>

                                            <h1 style="text-align:center;">Order items</h1>

                                            <form action="" method="post">
                                                <input type="submit" value="Back to my orders" name="my-cart" class="button">
                                            </form>
                                            <br><br>

                                            <?php
                                            foreach ($inforOrderItems as $order) {
                                            ?>
                                                <tr class="cart_item">
                                                    <td class="product-remove">
                                                        <?php echo $order['product_name']; ?>
                                                    </td>

                                                    <td class="product-thumbnail">
                                                        <a href="single_product.php?product-id=<?php echo $productId; ?>" target="_blank"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="../common/uploads/products/<?php echo $order['product_image']; ?>"></a>
                                                    </td>

                                                    <td class="product-name">
                                                        $<?php echo number_format((float)$order['product_price'], 2, '.', ''); ?>
                                                    </td>

                                                    <td class="product-price">
                                                        <?php echo $order['product_quantity']; ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else {
                                            ?>
                                            <tr>
                                                <td class="actions" colspan="4">
                                                    <b>Shopping cart order is empty<p>
                                                </td>
                                            </tr>
                                        <?php

                                        } ?>

                                    </tbody>
                                </table>
                            </form>

                            <div class="cart-collaterals">


                                <div class="cross-sells">
                                    <?php
                                    $listProductsRelated = getAllRelatedProducts($connection, $active);
                                    if (array_key_exists('shopping-cart', $_COOKIE)) {
                                        $cookieData = stripslashes($_COOKIE['shopping-cart']);
                                        $cartData = json_decode($cookieData, true);

                                        if (count($cartData) > 0) {
                                            echo '<h2>You may be interested in...</h2>';

                                            foreach ($listProductsRelated as $product) {
                                    ?>
                                                <ul class="products">
                                                    <li class="product">
                                                        <a href="single_product.php?product-id=<?php echo $product['id']; ?>">
                                                            <img width="325" height="325" alt="T_4_front" class="attachment-shop_catalog wp-post-image" src="../common/uploads/products/<?php echo $product['image']; ?>">
                                                            <h3><?php echo limitWordString($product['name']); ?></h3>
                                                            <span class="price">
                                                                <?php if ($product['old_price'] == '' || $product['old_price'] == '0') { ?>
                                                                    <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins>
                                                                <?php } else { ?>
                                                                    <ins>$<?php echo number_format((float)$product['price'], 2, '.', ''); ?></ins> <del>$<?php echo number_format((float)$product['old_price'], 2, '.', ''); ?></del>
                                                                <?php } ?>
                                                            </span>
                                                        </a>

                                                        <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="22" rel="nofollow" href="action_cart.php?action=add&product-id=<?php echo $product['id']; ?>">Select options</a>
                                                    </li>
                                                </ul>

                                            <?php
                                            }

                                            ?>
                                </div>


                                <div class="cart_totals ">
                                    <h2>Cart Totals</h2>

                                    <table cellspacing="0">
                                        <tbody>
                                            <tr class="cart-subtotal">
                                                <th>Cart Subtotal</th>
                                                <td><span class="amount">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span></td>
                                            </tr>

                                            <tr class="shipping">
                                                <th>Shipping and Handling</th>
                                                <td>Free Shipping</td>
                                            </tr>

                                            <tr class="order-total">
                                                <th>Order Total</th>
                                                <td><strong><span class="amount">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span></strong> </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        <?php }
                                    }
                        ?>
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