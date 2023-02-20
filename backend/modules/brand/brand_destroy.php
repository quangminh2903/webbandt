<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/brand_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get data id
        $id = !empty($_POST['delete_idn']) ? trim($_POST['delete_idn']) : '';

        // Get info brand by id
        $infoBrand = getInfoBrand($connection, $id);

        // Get product by brand's id
        $checkBrand = checkProductsBrand($connection, $id);

        $errors = [];

        // check exist product include brand
        if (count($checkBrand) > 0) {
            $errors['error'] = '.';
            $_SESSION['error'] = 'Exist products including brand';

            header('location: brand_list.php');
            exit();
        }

        // Destroy brand when not found any errors
        if (empty($errors)) {
            $_SESSION['success'] = 'Destroy brand ' . $infoBrand['name'] . ' successfully';

            // Delete image in folder uploads
            deleteImageBrandUpload($connection, $id);

            // Destroy brand by brand's id and brand's active
            destroyBrand($connection, $id, $activeDelete);

            header('location: brand_backup_list.php');
            exit();
        }
    }
?>