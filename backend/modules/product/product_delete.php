<?php
session_start();

require_once '../../../common/configs/connect.php';
require_once '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get id product's delete
    $id = !empty($_POST['delete_id']) ? trim($_POST['delete_id']) : '';

    // Get info product by product's id
    $infoProduct = getInfoProduct($connection, $id);

    $errors = [];

    // Delete product whent not found any errors
    if (empty($errors)) {
        $isBestSell = 0;
        $isNew = 0;

        // Delete product by product's id and product's active
        deleteProduct($connection, $activeDelete, $id, $isBestSell, $isNew);

        $_SESSION['success'] = 'Delete product <b>' . $infoProduct['name'] . '</b> successfully';

        header('location: product_list.php');
        exit();
    }
}
