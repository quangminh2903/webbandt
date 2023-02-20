<?php
    // Check info user apply file login.php
    function getUserInfo($connection, $username, $active) {
        $sqlUserLogin = 'SELECT `username`, `password` FROM `users` WHERE `username` = :username AND `active` = :active LIMIT 1';

        $objUserLogin = $connection->prepare($sqlUserLogin);

        $data = [
            ':username' => $username,
            ':active' => $active
        ];

        $objUserLogin->execute($data);

        return $objUserLogin->fetch(PDO::FETCH_OBJ);
    }

    // Check info username apply file login.php
    function checkInfo($connection, $username) {
        $sqlUserLogin = 'SELECT `username`, `password`, `active` FROM `users` WHERE `username` = :username LIMIT 1';

        $objUserLogin = $connection->prepare($sqlUserLogin);

        $data = [
            ':username' => $username
        ];

        $objUserLogin->execute($data);

        return $objUserLogin->fetch(PDO::FETCH_OBJ);
    }

    // Get email apply file forgot_password.php and user_add.php
    function getEmail($connection, $email) {
        $sqlSelectUser = 'SELECT `id`, `active` FROM `users` WHERE `email` = :email LIMIT 1';

        $objSelectUser = $connection->prepare($sqlSelectUser);

        $data = [
            ':email' => $email
        ];

        $objSelectUser->execute($data);

        return $objSelectUser->fetch(PDO::FETCH_ASSOC);
    }

    // Get user profile apply file profile.php and change_password.php
    function getUserProfile($connection, $username) {
        $sqlUser = 'SELECT * FROM `users` WHERE `username` = :username';

        $objUser = $connection->prepare($sqlUser);

        $data = [
            ':username' => $username
        ];
        $objUser->execute($data);

        return $objUser->fetch(PDO::FETCH_ASSOC);
    }

    // Get token apply file forgot_password.php
    function getToken($connection, $data) {
        $sqlToken = 'UPDATE `users` SET `token` = ? WHERE `id` = ?';

        $objToken = $connection->prepare($sqlToken);

        $objToken->execute($data);

        return $objToken->fetch(PDO::FETCH_ASSOC);
    }

    // Check token sql apply file reset_password.php
    function checkToken($connection, $token) {
        $sqlToken = 'SELECT `id`, `username`, `email` FROM `users` WHERE `token` = :token';

        $objToken = $connection->prepare($sqlToken);

        $data = [
            ':token' => $token
        ];

        $objToken->execute($data);

        return $objToken->fetch(PDO::FETCH_ASSOC);
    }

    // Reset pass apply file reset_password.php
    function resetPass($connection, $password, $token, $id) {
        $sqlResetPass = 'UPDATE `users` SET `password` = :password, `token` = :token WHERE `id` = :id';

        $objResetPass = $connection->prepare($sqlResetPass);

        $password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            ':password' => $password,
            ':token' => $token,
            ':id' => $id
        ];

        $objResetPass->execute($data);

        return $objResetPass->fetch(PDO::FETCH_ASSOC);
    }

    // Change password apply file change_password.php
    function changePassword($connection, $password, $id) {
        $sqlChangePass = 'UPDATE `users` SET `password` = :password WHERE `id` = :id';

        $objChangePass = $connection->prepare($sqlChangePass);

        $password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            ':password' => $password,
            ':id' => $id
        ];

        $objChangePass->execute($data);

        return $objChangePass->fetch(PDO::FETCH_ASSOC);
    }
?>