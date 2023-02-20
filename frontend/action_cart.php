<?php
    require_once '../common/configs/connect.php';
    require '../common/configs/constant.php';
    require './funcs/cart_funcs.php';

    session_start();

    $productId = $_GET['product-id'];
    $action = $_GET['action'];
    $quanityProduct = $_GET['quantity-product'];

    switch ($action) {
        case 'add':
            // Check isset cookie shopping cart
            if (isset($_COOKIE['shopping-cart'])) {
                $cookieData = stripslashes($_COOKIE['shopping-cart']);

                $cartData = json_decode($cookieData, true);
            } else {
                $cartData = [];
            }

            // Check isset productId exist in cookie shopping cart
            if (array_key_exists($productId, $cartData)) {
                $cartData[$productId]['quantity'] = $cartData[$productId]['quantity'] + 1;
            } else {
                $product = getProductById($connection, $active, $productId);

                $cartData[$productId] = [
                    'name' => $product['name'],
                    'image' => $product['image'],
                    'price' => $product['price'],
                    'quantity' => 1
                ];
            }

            $itemCount = count($cartData);

            setcookie('shopping-cart', json_encode($cartData), time() + (86400 * 30), '/');

            $_SESSION['success'] = 'Add to cart success';

            header('location: cart.php');
            exit();

            break;

        case 'delete':
            if (isset($_COOKIE['shopping-cart'])) {
                $cookieData = stripslashes($_COOKIE['shopping-cart']);

                $cartData = json_decode($cookieData, true);

                unset($cartData[$productId]);

                setcookie('shopping-cart', json_encode($cartData), time() + (86400 * 30), '/');
            }

            $_SESSION['success'] = 'Delete cart success';

            header('location: cart.php');
            exit();

            break;

        default:
            header('location: index.php');
            exit();
    }
