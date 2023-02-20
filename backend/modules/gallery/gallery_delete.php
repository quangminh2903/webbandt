<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/product_funcs.php';
    require_once '../../funcs/gallery_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get productId
        $productId = !empty($_GET['product_id']) ? trim($_GET['product_id']) : '';

        // Get info product by id
        $infoProduct = getInfoProduct($connection, $productId);

        // Get id delelte gallery by id
        $id = !empty($_POST['delete_id']) ? trim($_POST['delete_id']) : '';

        // Get info gallery by id
        $infoGalleryById = getInfoGalleryById($connection, $id);

        $errors = [];

        // Delete gallery by id when not found any errors
        if (empty($errors)) {
            // Delete gallery by id
            deleteGalleryById($connection, $id, $activeDelete);

            $_SESSION['success'] = 'Delete gallery <b>' . $infoGalleryById['id'] . '</b> successfully';

            header('location: gallery_list.php?product_id='. $productId .'');
            exit();
        }
    }
