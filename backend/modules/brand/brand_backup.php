<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/brand_funcs.php';


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id brand's backup
        $id = !empty($_POST['restore_id']) ? trim($_POST['restore_id']) : '';

        // Get info brand by brand's id
        $infoBrand = getInfoBrand($connection, $id);

        $errors = [];

        // Backup brand when not found any errors
        if (empty($errors)) {
            // Backup brand by brand's active and brand's id
            backupBrand($connection, $inactive, $id);

            $_SESSION['success'] = 'Restore brand <b>' . $infoBrand['name'] . '</b> successfully';

            header('location: brand_list.php');
            exit();
        }
    }
?>