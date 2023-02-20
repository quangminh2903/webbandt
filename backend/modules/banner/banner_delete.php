<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require '../../../common/configs/constant.php';
    require_once '../../funcs/banner_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id delete
        $id = !empty($_POST['delete_id']) ? trim($_POST['delete_id']) : '';

        // Get info banner
        $infoBanner = getInfoBanner($connection, $id);

        $errors = [];

        // Delete banner when not found any errors
        if (empty($errors)) {
            // Delete banner by banner's id
            deleteBanner($connection, $id, $activeDelete);

            $_SESSION['success'] = 'Delete banner <b>' . $infoBanner['name'] . '</b> successfully';

            header('location: banner_list.php');
            exit();
        }
    }
?>