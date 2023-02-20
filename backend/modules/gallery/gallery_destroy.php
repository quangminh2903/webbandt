<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/product_funcs.php';
    require_once '../../funcs/gallery_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get productId
        $productId = !empty($_GET['product_id']) ? trim($_GET['product_id']) : '';

        // Get infoProduct by productId
        $infoProduct = getInfoProduct($connection, $productId);

        // Get id destroy gallery by id
        $id = !empty($_POST['delete_idn']) ? trim($_POST['delete_idn']) : '';

        // Get info galley by id
        $infoGalleryById = getInfoGalleryById($connection, $id);

        $errors = [];

        // Destroy gallery when not found any errors
        if (empty($errors)) {
            // Delete image gallery upload
            deleteImageGalleryUpload($connection, $id);

            // Destroy gallery
            destroyGallery($connection, $id, $activeDelete);

            $_SESSION['success'] = 'Destroy image <b>' . $infoGalleryById['id'] . '</b> successfully';

            header('location: gallery_backup_list.php?product_id='. $productId .'');
            exit();
        }
    }
