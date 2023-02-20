<?php
    session_start();

    setcookie('username', $username, time() - 1);
    setcookie('password', $password, time() - 1);

    unset($_SESSION['username']);

    $_SESSION['success'] = 'Logout success';

    header('location: ./login.php');
    exit();
?>