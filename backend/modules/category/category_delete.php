<?php
    session_start();

    require_once '../../../common/configs/connect.php';
    require_once '../../../common/configs/constant.php';
    require_once '../../funcs/category_funcs.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get data id category's delete
        $id = !empty($_POST['delete_id']) ? trim($_POST['delete_id']) : '';

        // Get info category by category's id
        $infoCategories = getInfoCategories($connection, $id);

        // Get product by category's id
        $checkCategory = checkProductCategory($connection, $id);

        $errors = [];

        // check exist product include category
        if (count($checkCategory) > 0) {
            $errors['error'] = '.';
            $_SESSION['error'] = 'Exist products including category';

            header('location: category_list.php');
            exit();
        }

        // Delete category when not found any errors
        if (empty($errors)) {
            // Delete category by category's id and category's active
            deleteCategories($connection, $activeDelete, $id);

            $_SESSION['success'] = 'Delete category <b>' . $infoCategories['name'] . '</b> successfully';

            header('location: category_list.php');
            exit();
        }
    }
