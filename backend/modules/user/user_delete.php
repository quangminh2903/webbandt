<?php
session_start();

require_once '../../../common/configs/connect.php';
require_once '../../../common/configs/constant.php';
require_once '../../funcs/user_funcs.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = !empty($_POST['delete_id']) ? trim($_POST['delete_id']) : '';

    // Get info user by user's id
    $infoUser = getInfoUser($connection, $id);

    $errors = [];

    // Validate user is logging can't delete itself
    if ($infoUser['username'] === $_SESSION['username'] || $infoUser['username'] === $_COOKIE['username']) {
        $errors['delete'] = '.';
        $_SESSION['error'] = 'You are logged in with this account and you cannot delete it !';

        header('location: user_list.php');
        exit();
    }

    // Delete user when not found any error
    if (empty($errors)) {
        deleteUser($connection, $activeDelete, $id);

        $_SESSION['success'] = 'Delete user <b>' . $infoUser['username'] . '</b> successfully';

        header('location: user_list.php');
        exit();
    }
}
