<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/brand_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get data id
        $id = !empty($_POST['delete_id']) ? trim($_POST['delete_id']) : '';

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

        // Delete brand when not found any errors
        if (empty($errors)) {
            // Delete brand by brand's active and brand's id
            deleteBrand($connection, $id, $activeDelete);

            $_SESSION['success'] = 'Delete brand <b>' . $infoBrand['name'] . '</b> successfully';

            header('location: brand_list.php');
            exit();
        }
    }
?>