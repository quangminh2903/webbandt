<?php
session_start();

require_once '../../../common/configs/connect.php';
require_once '../../../common/configs/constant.php';
require_once '../../funcs/product_funcs.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get id product's backup
    $id = !empty($_POST['restore_id']) ? trim($_POST['restore_id']) : '';

    // Get info product by id
    $infoProduct = getInfoProduct($connection, $id);

    $errors = [];

    // Backup product whent not found any errors
    if (empty($errors)) {
        // Backup product by product's id and product's active
        backupProduct($connection, $inactive, $id);

        $_SESSION['success'] = 'Backup product <b>' . $infoProduct['name'] . '</b> successfully';

        header('location: product_list.php');
        exit();
    }
}
