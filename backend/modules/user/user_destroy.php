<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/user_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id product when delete
        $id = !empty($_POST['delete_idn']) ? trim($_POST['delete_idn']) : '';

        // Get info user by user's id
        $infoUser = getInfoUser($connection, $id);

        $errors = [];

        // Destroy user when not found error
        if (empty($errors)) {
            $_SESSION['success'] = 'Destroy username <b>' . $infoUser['username'] . '</b> successfully';

            // Destroy user by user's id and user's active
            destroyUser($connection, $id, $activeDelete);

            header('location: user_list_backup.php');
            exit();
        }
    }
?>