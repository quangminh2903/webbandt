<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/category_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get id category's destroy
        $id = !empty($_POST['delete_idn']) ? trim($_POST['delete_idn']) : '';

        // Get info category by category's id
        $infoCategory = getInfoCategories($connection, $id);

        // Get product by category's id
        $checkCategory = checkProductCategory($connection, $id);

        $errors = [];

        // check exist product include category
        if (count($checkCategory) > 0) {
            $errors['error'] = '.';
            $_SESSION['error'] = 'Exist products including category';

            header('location: category_backup_list.php');
            exit();
        }

        // Destroy category by id when not found any errors
        if (empty($errors)) {
            $_SESSION['success'] = 'Delete category <b>' . $infoCategory['name'] . '</b> successfully';

            // Destroy category by category's id and category's active
            destroyCategory($connection, $activeDelete, $id);

            header('location: category_backup_list.php');
            exit();
        }
    }
?>