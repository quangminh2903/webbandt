<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require '../../../common/configs/constant.php';
    require_once '../../funcs/banner_funcs.php';


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id banner's backup
        $id = !empty($_POST['restore_id']) ? trim($_POST['restore_id']) : '';

        // Get info banner by banner's id
        $infoBanner = getInfoBanner($connection, $id);

        $errors = [];

        // Backup banner when not found any errors
        if (empty($errors)) {
            backupBanner($connection, $inactive, $id);

            $_SESSION['success'] = 'Backup banner <b>' . $infoBanner['name'] . '</b> successfully';

            header('location: banner_list.php');
            exit();
        }
    }
?>