<?php
    require_once '../common/configs/connect.php';
    require '../common/configs/constant.php';
    require_once './common/validate.php';
    require_once './funcs/index_funcs.php';
    require_once './funcs/auth_funcs.php';
    require_once './funcs/single_product_funcs.php';
    require './funcs/cart_funcs.php';

    session_start();
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    // Get list id reviewed recently
    if (array_key_exists('viewed_products', $_COOKIE)) {
        $cookie = $_COOKIE['viewed_products'];

        $cookieId = unserialize($cookie);
    }

    if (!array_key_exists('shopping-cart', $_COOKIE)) {
        header('location: index.php');
        exit();
    } else {
        $cookieData = stripslashes($_COOKIE['shopping-cart']);

        $cartData = json_decode($cookieData, true);

        if (count($cartData) <= 0) {
            header('location: index.php');
            exit();
        }
    }

    if (!empty($_SESSION['account']['email'])) {
        $email = $_SESSION['account']['email'];

        $profileUser = getUserProfile($connection, $email);
    }


    if (isset($_POST['customer-login'])) {
        $errors = [];

        // Get data form
        $email = !empty($_POST['email']) ? trim($_POST['email']) : '';
        $password = !empty($_POST['password']) ? trim($_POST['password']) : '';

        $checkEmail = checkInfo($connection, $email);

        // Validate customer's email
        if (!checkRequire($email)) {
            $errors['email']['required'] = 'Please type email !';
        } else {
            if (!checkValidEmail($email)) {
                $errors['email']['not_valid'] = 'Please type valid email address !';
            } else {
                if (empty($checkEmail)) {
                    $errors['email']['not_exist'] = 'Not exist Email';
                } else {
                    $checActive = $checkEmail->active;

                    if ($checActive == '0') {
                        $errors['email']['inactive'] = 'Account inactive';
                    } elseif ($checActive == '2') {
                        $errors['email']['delete'] = 'Account has been locked';
                    } elseif ($checActive == '3') {
                        $errors['email']['ban'] = 'Account has been ban';
                    }
                }
            }
        }

        // Validate customer's password
        if (!checkRequire($password)) {
            $errors['password']['required'] = '<p>Please type password !</p>';
        }

        if ($email !== '' && $password !== '') {
            $active = 1;
            $checkInfo = getUserInfo($connection, $email, $active);

            if (empty($checkInfo)) {
                $errors['wrong'] = 'Wrong password or email !';
            } else {
                $passInfo = $checkInfo->password;
                $checkPass = password_verify($password, $passInfo);

                if (!$checkPass) {
                    $errors['wrong'] = 'Wrong password or email !';
                    $_SESSION['error'] = 'Wrong password or email !';
                }
            }
        }

        // Login when not found any errors
        if (empty($errors)) {
            $_SESSION['account']['email'] = $email;

            $checkName = $checkEmail->name;
            $_SESSION['account']['name'] = $checkName;

            if (!empty($remember)) {
                setcookie('email_shop', $email, time() + 86400);
                setcookie('name_shop', $checkName, time() + 86400);
                setcookie('password_shop', $password, time() + 86400);
            }

            $_SESSION['success'] = 'Login success';

            header('location: ./checkout.php');
        }
    }

    if (isset($_POST['checkout'])) {
        $errors = [];

        // Get data form
        $customerEmail = !empty($_POST['customer_email']) ? trim($_POST['customer_email']) : '';
        $customerName = !empty($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
        $customerPhone = !empty($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
        $totalMoney = !empty($_POST['total-money']) ? trim($_POST['total-money']) : '';
        $totalAllProduct = !empty($_POST['total-products']) ? trim($_POST['total-products']) : '';

        // Validate customer's name
        if (!checkRequire($customerName)) {
            $errors['name']['required'] = 'Please type name !';
        }

        // Validate customer's phone
        if (!checkRequire($customerPhone)) {
            $errors['phone']['required'] = 'Please type phone !';
        } else {
            if (!checkNumber($customerPhone)) {
                $errors['phone']['not_type'] = 'Please type number phone';
            } else {
                if (!validateMobile($customerPhone)) {
                    $errors['phone']['not_valid'] = 'Please type valid phone !';
                }
            }
        }

        // Checkout when not found any errors
        if (empty($errors)) {
            $status = 0;

            $data = [
                $customerName,
                $customerPhone,
                $customerEmail,
                $totalMoney,
                $totalAllProduct,
                date('Y-m-d H:i:s'),
                $status
            ];
            createOrders($connection, $data);

            $orderId = $connection->lastInsertId();

            if (array_key_exists('shopping-cart', $_COOKIE)) {
                $cookieData = stripslashes($_COOKIE['shopping-cart']);
                $cartData = json_decode($cookieData, true);

                if (count($cartData) > 0) {
                    foreach ($cartData as $productId => $data) {
                        $data = [
                            $orderId,
                            $productId,
                            $data['name'],
                            $data['image'],
                            $data['price'],
                            $data['quantity']
                        ];

                        createOrderItem($connection, $data);

                        unset($cartData[$productId]);

                        setcookie('shopping-cart', json_encode($cartData), time() + (86400 * 30), '/');
                    }
                }
            }

            $_SESSION['success'] = 'Congratulations on your successful order, please wait for us to contact you';

            header('location: ./index.php');
            exit();
        }
    }
?>

<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<?php $pageTitle = 'Checkout'; ?>
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
                            <?php
                            if (empty($_SESSION['account']['email'])) {
                            ?>
                                <div class="woocommerce-info">
                                    If you want to confirm your order, please login!
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <form action="" method="post" id="form-login">
                                        <h1 class="form-heading" style="text-align: center;">Login</h1>
                                        <div class="form-group">
                                            <i class="fa fa-envelope"></i>&nbsp;&nbsp;
                                            <input type="text" class="form-input" style="width: 290px" placeholder="Email" name="email" value="<?php echo !empty($email) ? $email : ''; ?>">
                                            &nbsp;
                                            &nbsp;
                                            <span class="required" style="color: red;">*</span>
                                        </div>

                                        <div class="form-group"></div>

                                        <div class="form-group">
                                            <i class="fa fa-key"></i>&nbsp;&nbsp;
                                            <input type="password" class="form-input" placeholder="Password" name="password" value="" style="width: 290px">
                                            &nbsp;
                                            &nbsp;
                                            <span class="required" style="color: red;">*</span>
                                        </div>
                                        <?php require_once './lib/error_pass.php'; ?>

                                        <div class="form-group">
                                            <label class="inline" for="rememberme"><input id="Field" name="remember" type="checkbox" class="field login-checkbox" value="1" tabindex="4" <?php echo !empty($remember) ? 'checked="checked"' : '' ?> />&emsp; Remember me </label>
                                        </div>

                                        <div style="text-align: center;">
                                        <button type="submit" class="form-submit" name="customer-login">login</button>
                                        </div>
                                        <div>
                                            <br>
                                            <a href="./auth/register.php">Don't have an account? Register now</a>
                                            <br><br>
                                            <a href="./auth/forgot_password.php">Lost your password ?</a>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3"></div>
                            <?php
                            } else {
                            ?>
                                <form enctype="multipart/form-data" action="" class="checkout" method="post">

                                    <div id="customer_details" class="col2-set">
                                        <div class="col-1">
                                            <div class="woocommerce-billing-fields">
                                                <h3>Billing Details</h3>
                                                <p id="billing_first_name_field" class="form-row form-row-first validate-required">
                                                    <label class="" for="billing_first_name">Name <span style="color:red;">*</span>
                                                    </label>
                                                    <input type="text" value="<?php echo $profileUser['name']; ?>" placeholder="" id="billing_first_name" name="customer_name" class="input-text ">
                                                    <?php require_once './lib/error_name.php'; ?>
                                                </p>


                                                <p id="billing_last_name_field" class="form-row form-row-last validate-required">
                                                    <label class="" for="billing_last_name">Email
                                                    </label>
                                                    <input type="text" value="<?php echo $profileUser['email']; ?>" placeholder="" id="billing_last_name" name="customer_email" class="input-text " readonly>
                                                </p>
                                                <div class="clear"></div>

                                                <p id="billing_company_field" class="form-row form-row-wide">
                                                    <label class="" for="billing_company">Phone <span style="color:red;">*</span></label>
                                                    <input type="text" value="<?php echo $profileUser['phone']; ?>" placeholder="" id="billing_company" name="customer_phone" class="input-text ">
                                                    <?php require_once './lib/error_phone.php'; ?>
                                                </p>

                                                <div class="clear"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <h3 id="order_review_heading">Your order</h3>

                                    <div id="order_review" style="position: relative;">
                                        <table class="shop_table">
                                            <thead>
                                                <tr>
                                                    <th class="product-name">Product</th>
                                                    <th class="product-total">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="cart_item">
                                                    <td class="product-name">
                                                        Ship Your Idea <strong class="product-quantity">× <?php echo $totalProduct; ?></strong>
                                                        <input type="hidden" name="total-products" value="<?php echo $totalProduct; ?>">
                                                    </td>
                                                    <td class="product-total">
                                                        <span class="amount">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>

                                                <tr class="cart-subtotal">
                                                    <th>Cart Subtotal</th>
                                                    <td><span class="amount">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span>
                                                    </td>
                                                </tr>

                                                <tr class="shipping">
                                                    <th>Shipping and Handling</th>
                                                    <td>

                                                        Free Shipping
                                                        <input type="hidden" class="shipping_method" value="free_shipping" id="shipping_method_0" data-index="0" name="shipping_method[0]">
                                                    </td>
                                                </tr>

                                                <tr class="order-total">
                                                    <th>Order Total</th>
                                                    <td>
                                                        <strong>
                                                            <span class="amount">$<?php echo number_format((float)$totalAmount, 2, '.', ''); ?></span>
                                                        </strong>
                                                        <input type="hidden" name="total-money" value="<?php echo $totalAmount; ?>">
                                                    </td>
                                                </tr>

                                            </tfoot>
                                        </table>


                                        <div id="payment">
                                            <ul class="payment_methods methods">
                                                <li class="payment_method_bacs">
                                                    <input type="radio" data-order_button_text="" checked="checked" value="bacs" name="payment_method" class="input-radio" id="payment_method_bacs">
                                                    <label for="payment_method_bacs">Ship Cod</label>

                                                </li>
                                            </ul>

                                            <div class="form-row place-order">

                                                <input type="submit" data-value="Place order" value="Place order" id="place_order" name="checkout" class="button alt">

                                            </div>

                                            <div class="clear"></div>

                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
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