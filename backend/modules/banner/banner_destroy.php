<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require '../../../common/configs/constant.php';
    require_once '../../funcs/banner_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id destroy
        $id = !empty($_POST['delete_idn']) ? trim($_POST['delete_idn']) : '';

        // Get info banner
        $infoBanner = getInfoBanner($connection, $id);

        $errors = [];

        // Delete banner when not found any errors
        if (empty($errors)) {
            $_SESSION['success'] = 'Destroy banner <b>' . $infoBanner['name'] . '</b> successfully';

            // Delete banner's image
            deleteImageBannerUpload($connection, $id);

            // Delete banner by banner's id and banner's active
            destroyBanner($connection, $id, $activeDelete);

            header('location: banner_backup_list.php');
            exit();
        }
    }
?>