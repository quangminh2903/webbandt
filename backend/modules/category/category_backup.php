<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/category_funcs.php';


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get data id category's backup
        $id = !empty($_POST['restore_id']) ? trim($_POST['restore_id']) : '';

        // Get data info category by category's id
        $infoCategory = getInfoCategories($connection, $id);

        $errors = [];

        // Back up category by id when not found any errors
        if (empty($errors)) {
            // Backup category by category's id and category's active
            backupCategory($connection, $inactive, $id);

            $_SESSION['success'] = 'Restore category <b>' . $infoCategory['name'] . '</b> successfully';

            header('location: category_list.php');
            exit();
        }
    }
?>