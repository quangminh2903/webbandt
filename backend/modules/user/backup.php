<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/user_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id user's backup
        $id = !empty($_POST['restore_id']) ? trim($_POST['restore_id']) : '';

        // Get info user by user's id
        $infoUser = getInfoUser($connection, $id);

        $errors = [];

        // Backup user when not found any errors
        if (empty($errors)) {
            // Backup user by user's active and user's id
            backupUser($connection, $inactive, $id);

            $_SESSION['success'] = 'Restore user <b>' . $infoUser['username'] . '</b> successfully';

            header('location: user_list.php');
            exit();
        }
    }
?>