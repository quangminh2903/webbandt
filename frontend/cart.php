<?php
    require_once '../common/configs/connect.php';
    require '../common/configs/constant.php';
    require_once './funcs/single_product_funcs.php';

    session_start();

    // Get list id reviewed recently
    if (array_key_exists('viewed_products', $_COOKIE)) {
        $cookie = $_COOKIE['viewed_products'];
        $cookieId = unserialize($cookie);
    }

    $totalPrice = 0;
    $subTotal = 0;

    // Check isset cookie shopping cart
    if (array_key_exists('shopping-cart', $_COOKIE)) {
        $cookieData = stripslashes($_COOKIE['shopping-cart']);

        $cartData = json_decode($cookieData, true);

        if (isset($_POST['update-cart'])) {
            if (count($cartData) > 0) {
                foreach ($cartData as $productId => $cart) {
                    if ($_POST[$productId] <= 0) {
                        unset($cartData[$productId]);

                        setcookie('shopping-cart', json_encode($cartData), time() + (86400 * 30), '/');
                    } else {
                        $cartData[$productId]['quantity'] = $_POST[$productId];

                        setcookie('shopping-cart', json_encode($cartData), time() + (86400 * 30), '/');
                    }
                }
            }

            $_SESSION['success'] = 'Update cart success';

            header('location: cart.php');
            exit();
        }
    }

    if (isset($_POST['checkout'])) {
        header('location: checkout.php');
        exit();
    }

    if (!isset($totalAmount)) {
        $totalAmount = 0;
    }

    $totalQuantity = 0;
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Cart'; ?>
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
                                            <th class="product-remove">&nbsp;</th>
                                            <th class="product-thumbnail">Image</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (array_key_exists('shopping-cart', $_COOKIE)) {
                                            $cookieData = stripslashes($_COOKIE['shopping-cart']);
                                            $cartData = json_decode($cookieData, true);

                                            if (count($cartData) > 0) {

                                                foreach ($cartData as $productId => $data) {
                                                    $subTotal = ($data['price'] * $data['quantity']);
                                        ?>
                                                    <tr class="cart_item">
                                                        <td class="product-remove">
                                                            <a title="Remove this item" class="remove" href="action_cart.php?action=delete&product-id=<?php echo $productId; ?>">×</a>
                                                        </td>

                                                        <td class="product-thumbnail">
                                                            <a href="single_product.php?product-id=<?php echo $productId; ?>" target="_blank"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="../common/uploads/products/<?php echo $data['image']; ?>"></a>
                                                        </td>

                                                        <td class="product-name">
                                                            <a href="single_product.php?product-id=<?php echo $productId; ?>" target="_blank"><?php echo $data['name']; ?></a>
                                                        </td>

                                                        <td class="product-price">
                                                            <span class="amount">$<?php echo number_format((float)$data['price'], 2, '.', ''); ?></span>
                                                        </td>

                                                        <td class="product-quantity">
                                                            <div class="quantity buttons_added">
                                                                <input type="button" class="minus" id="decrease" onclick="decreaseValue('<?php echo $productId; ?>_input')" value="-">
                                                                <input type="number" size="4" class="input-text qty text" title="Qty" name="<?php echo $productId; ?>" value="<?php echo $data['quantity']; ?>" min="0" step="1" id="<?php echo $productId; ?>_input">
                                                                <input type="button" class="plus" id="increase" onclick="increaseValue('<?php echo $productId; ?>_input')" value="+">
                                                            </div>
                                                        </td>

                                                        <td class="product-subtotal">
                                                            <span class="amount">
                                                                $<?php echo number_format((float)$subTotal, 2, '.', ''); ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td class="actions" colspan="6">
                                                        <input type="submit" value="Update Cart" name="update-cart" class="button">
                                                        <input type="submit" value="Checkout" name="checkout" class="checkout-button button alt wc-forward">
                                                    </td>
                                                </tr>
                                            <?php
                                            } else {
                                            ?>
                                                <tr>
                                                    <td class="actions" colspan="6">
                                                        <b>Shopping cart is empty<p>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td class="actions" colspan="6">
                                                    <b>Shopping cart is empty<p>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </form>

                            <div class="cart-collaterals">


                                <div class="cross-sells">
                                    <?php
                                    $active = 1;

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

    <script>
        function increaseValue(item) {
            let value = parseInt(document.getElementById(item).value, 10);
            value = isNaN(value) ? 0 : value;
            value++;
            document.getElementById(item).value = value;
        }

        function decreaseValue(item) {
            let value = parseInt(document.getElementById(item).value, 10);
            value = isNaN(value) ? 0 : value;
            value < 1 ? value = 1 : '';
            value--;
            document.getElementById(item).value = value;
        }
    </script>
</body>

</html>