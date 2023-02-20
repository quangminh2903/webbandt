<?php
function getUserInfo($connection, $email, $active)
{
    $sqlUserLogin = 'SELECT `email`, `password` FROM `customers` WHERE `email` = :email AND `active` = :active LIMIT 1';

    $objUserLogin = $connection->prepare($sqlUserLogin);

    $data = [
        ':email' => $email,
        ':active' => $active
    ];
    $objUserLogin->execute($data);

    return $objUserLogin->fetch(PDO::FETCH_OBJ);
}

function checkInfo($connection, $email)
{
    $sqlUserLogin = 'SELECT * FROM `customers` WHERE `email` = :email LIMIT 1';

    $objUserLogin = $connection->prepare($sqlUserLogin);

    $data = [
        ':email' => $email
    ];
    $objUserLogin->execute($data);

    return $objUserLogin->fetch(PDO::FETCH_OBJ);
}

function getEmail($connection, $email)
{
    $sqlSelectUser = 'SELECT * FROM `customers` WHERE `email` = :email LIMIT 1';

    $objSelectUser = $connection->prepare($sqlSelectUser);

    $data = [
        ':email' => $email
    ];
    $objSelectUser->execute($data);

    return $objSelectUser->fetch(PDO::FETCH_ASSOC);
}

function getPhone($connection, $phone)
{
    $sqlSelect = 'SELECT `phone` FROM `customers` WHERE `phone` = :phone LIMIT 1';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':phone' => $phone
    ];
    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

function registerUser($name, $phone, $email, $password)
{
    global $connection;

    $sqlRegister = 'INSERT INTO `customers`(`name`, `phone`, `email`, `password`) VALUES(:name, :phone, :email, :password)';

    $objRegister = $connection->prepare($sqlRegister);

    $password = password_hash($password, PASSWORD_BCRYPT);
    $data = [
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email,
        ':password' => $password
    ];

    return $objRegister->execute($data);
}

function getToken($connection, $data)
{
    $sqlToken = 'UPDATE `customers` SET `token` = ? WHERE `id` = ?';

    $objToken = $connection->prepare($sqlToken);

    $objToken->execute($data);

    return $objToken->fetch(PDO::FETCH_ASSOC);
}

function checkToken($connection, $token) {
    $sqlToken = 'SELECT * FROM `customers` WHERE `token` = :token';

    $objToken = $connection->prepare($sqlToken);

    $data = [
        ':token' => $token
    ];

    $objToken->execute($data);

    return $objToken->fetch(PDO::FETCH_ASSOC);
}

function resetPass($connection, $password, $token, $id) {
    $sqlResetPass = 'UPDATE `customers` SET `password` = :password, `token` = :token WHERE `id` = :id';

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

function changePassword($connection, $password, $id) {
    $sqlChangePass = 'UPDATE `customers` SET `password` = :password WHERE `id` = :id';

    $objChangePass = $connection->prepare($sqlChangePass);

    $password = password_hash($password, PASSWORD_BCRYPT);

    $data = [
        ':password' => $password,
        ':id' => $id
    ];
    $objChangePass->execute($data);

    return $objChangePass->fetch(PDO::FETCH_ASSOC);
}

function getUserProfile($connection, $email) {
    $sqlUser = 'SELECT * FROM `customers` WHERE `email` = :email';
    $objUser = $connection->prepare($sqlUser);

    $data = [
        ':email' => $email
    ];
    $objUser->execute($data);

    return $objUser->fetch(PDO::FETCH_ASSOC);
}