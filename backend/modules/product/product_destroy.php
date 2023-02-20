<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/product_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id product's destroy
        $id = !empty($_POST['delete_idn']) ? trim($_POST['delete_idn']) : '';

        // Get info product by product's id
        $infoProduct = getInfoProduct($connection, $id);

        $errors = [];

        // Destroy product whent not found any errors
        if (empty($errors)) {
            $_SESSION['success'] = 'Destroy product <b>' . $infoProduct['name'] . '</b> successfully';

            // Delete product's image
            deleteImageProductUpload($connection, $id);

            // Destroy product by product's id and product's active
            destroyProduct($connection, $id, $activeDelete);

            header('location: product_backup_list.php');
            exit();
        }
    }
?>