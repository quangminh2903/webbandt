<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/product_funcs.php';
    require_once '../../funcs/gallery_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get product id
        $productId = !empty($_GET['product_id']) ? trim($_GET['product_id']) : '';

        // Get info product by product id
        $infoProduct = getInfoProduct($connection, $productId);

        // Get id back up
        $id = !empty($_POST['restore_id']) ? trim($_POST['restore_id']) : '';

        // Get info gallery by id
        $infoGalleryById = getInfoGalleryById($connection, $id);

        $errors = [];

        // Backup gallery when not found any errors
        if (empty($errors)) {
            // Backup gallery by id
            backupGallery($connection, $inactive, $id);

            $_SESSION['success'] = 'Backup <b>' . $infoGalleryById['id'] . '</b> successfully';

            header('location: gallery_list.php?product_id='. $productId .'');
            exit();
        }
    }
