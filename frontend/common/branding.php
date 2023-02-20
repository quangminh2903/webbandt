<?php
    $currentUrl = getCurrentUrl();

    $authUrl = ($currentUrl == 'login.php' || $currentUrl == 'register.php' || $currentUrl == 'forgot_password.php' || $currentUrl == 'reset_password.php' || $currentUrl == 'change_password.php' || $currentUrl == 'profile.php');
?>

<div class="site-branding-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="logo">
                    <h1>
                        <?php if ($authUrl) { ?>
                            <a href="../index.php"><img src="../theme/img/logo.png" /></a>
                        <?php } else { ?>
                            <a href="./"><img src="theme/img/logo.png" /></a>
                        <?php } ?>
                    </h1>
                </div>
            </div>

            <div class="col-sm-6">

                <div class="shopping-item">
                    <?php
                    $totalProduct = 0;
                    $totalPrice = 0;

                    if (!isset($totalAmount)) {
                        $totalAmount = 0;
                    }
                    $totalQuantity = 0;

                    if (array_key_exists('shopping-cart', $_COOKIE)) {
                        $cookieData = stripslashes($_COOKIE['shopping-cart']);
                        $cartData = json_decode($cookieData, true);

                        if (count($cartData) > 0) {
                            foreach ($cartData as $productId => $data) {
                                $totalProduct += $data['quantity'];
                                $totalAmount = $totalAmount + $data['price'] * $data['quantity'];
                            }
                            if ($authUrl) { ?>
                                <a href="../cart.php">Cart - <span class="cart-amunt">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="product-count"><?php echo $totalProduct; ?></span></a>
                            <?php } else { ?>
                                <a href="cart.php">Cart - <span class="cart-amunt">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="product-count"><?php echo $totalProduct; ?></span></a>
                            <?php }
                        } else {
                            if ($authUrl) { ?>
                                <a href="../cart.php">Cart - <span class="cart-amunt">$0</span>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="product-count">0</span></a>
                            <?php } else { ?>
                                <a href="cart.php">Cart - <span class="cart-amunt">$0</span>
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="product-count">0</span></a>
                            <?php }
                        }
                    } else {
                        if ($authUrl) { ?>
                            <a href="../cart.php">Cart - <span class="cart-amunt">$0</span>
                                <i class="fa fa-shopping-cart"></i>
                                <span class="product-count">0</span></a>
                        <?php } else { ?>
                            <a href="cart.php">Cart - <span class="cart-amunt">$0</span>
                                <i class="fa fa-shopping-cart"></i>
                                <span class="product-count">0</span></a>
                    <?php }
                    } ?>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End site branding area -->