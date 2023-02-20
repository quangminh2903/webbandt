<?php
require_once '../../../common/configs/connect.php';
require_once '../../funcs/order_funcs.php';

session_start();

$action = $_GET['action'];

// check isset session username
if (empty($_SESSION['username'])) {
    header('location: ../auth/login.php');
    exit();
}

// Check order id
if (empty($_GET['order-id']) || !is_numeric($_GET['order-id'])) {
    $_SESSION['error'] = 'Not exist order id';

    header('location: order_list.php');
    exit();
}

$orderId = !empty($_GET['order-id']) ? trim($_GET['order-id']) : '';

// Get all list order items by order id
$inforOrderItems = getInfoOrders($connection, $orderId);

if (empty($inforOrderItems)) {
    $_SESSION['error'] = 'Not exist order id';

    header('location: order_list.php');
    exit();
}

switch ($action) {
    case 'complete':
        $status = 1;
        actionOrder($connection, $status, $orderId);

        $_SESSION['success'] = 'Action order success';

        header('location: order_list.php');
        exit();

        break;

    case 'cancel':
        $status = 2;
        actionOrder($connection, $status, $orderId);

        $_SESSION['success'] = 'Action order success';

        header('location: order_list.php');
        exit();

        break;

    case 'undo':
        $status = 0;
        actionOrder($connection, $status, $orderId);

        $_SESSION['success'] = 'Undo order success';

        header('location: order_list.php');
        exit();

        break;

    default:
        header('location: order_list.php');
        exit();
}
