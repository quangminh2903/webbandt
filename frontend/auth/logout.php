<?php
    session_start();

    setcookie('username', $username, time() - 1);
    setcookie('name_shop', $checkName, time() - 1);
    setcookie('password', $password, time() - 1);

    unset($_SESSION['account']['email']);

    $_SESSION['success'] = 'Logout success';
    header('location: ./login.php');
    exit();
?>