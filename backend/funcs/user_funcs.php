<?php
// Get all users apply file user_list.php
function getAllUsers($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `users` WHERE `active` != :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// List all user pagination apply user_list.php
function getAllUserPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `users` WHERE `active` != :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// List users when search apply user_list.php
function searchUsers($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearchUsers = 'SELECT * FROM `users` WHERE (`username` LIKE :keyword OR `email` LIKE :keyword) AND `active` != :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearchUsers = $connection->prepare($sqlSearchUsers);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearchUsers->execute($data);

    return $objSearchUsers->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search user apply user_list.php
function countTotalSearchUser($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `users` WHERE (`username` LIKE :keyword OR `email` LIKE :keyword) AND `active` != :active';
    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search deleted apply file user_list_backup.php
function countTotalSearchDeleted($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `users` WHERE (`username` LIKE :keyword OR `email` LIKE :keyword) AND `active` = :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];
    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get all deleted users apply file user_list_backup.php
function getAllDeletedUsers($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `users` WHERE `active` = :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search deleted users apply file user_list_backup.php
function searchDeletedUsers($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearchUsers = 'SELECT * FROM `users` WHERE (`username` LIKE :keyword OR `email` LIKE :keyword) AND `active` = :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearchUsers = $connection->prepare($sqlSearchUsers);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearchUsers->execute($data);

    return $objSearchUsers->fetchAll(PDO::FETCH_ASSOC);
}

// Get all deleted user pagination apply file user_list_backup.php
function getAllDeletedUserPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `users` WHERE `active` = :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Create user apply file user_add.php
function createUser($connection, $data)
{
    $sqlInsertUser = 'INSERT INTO `users`(`username`, `email`, `password`, `active`) VALUES (?, ?, ?, ?)';

    $objInsertUser = $connection->prepare($sqlInsertUser);

    return $objInsertUser->execute($data);
}

// Get user apply file user_add.php
function getUser($connection, $username)
{
    $sqlSelectUser = 'SELECT `username` FROM `users` WHERE `username` = :username LIMIT 1';

    $objSelectUser = $connection->prepare($sqlSelectUser);

    $data = [
        ':username' => $username
    ];

    $objSelectUser->execute($data);

    return $objSelectUser->fetch(PDO::FETCH_ASSOC);
}

// Get info user edit apply file user_edit.php
function getInfoUserEdit($connection, $id, $active)
{
    $sqlSelectUser = 'SELECT * FROM `users` WHERE `id` = ? and `active` != ?';

    $objSelectUser = $connection->prepare($sqlSelectUser);

    $data = [
        $id,
        $active
    ];

    $objSelectUser->execute($data);

    return $objSelectUser->fetch(PDO::FETCH_ASSOC);
}

// Update user with out pass apply file user_edit.php
function updateUserWithOutPass($connection, $data)
{
    $sqlUpdateUser = 'UPDATE `users` SET `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdateUser);

    return $objUpdate->execute($data);
}

// Update user apply file user_edit.php
function updateUser($connection, $data)
{
    $sqlUpdateUser = 'UPDATE `users` SET `password` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdateUser);

    return $objUpdate->execute($data);
}

// Get infor user apply file user_destroy.php, user_delete.php and backup.php
function getInfoUser($connection, $id)
{
    $sqlSelectUser = 'SELECT * FROM `users` WHERE `id` = :id';

    $objSelectUser = $connection->prepare($sqlSelectUser);

    $data = [
        ':id' => $id
    ];

    $objSelectUser->execute($data);

    return $objSelectUser->fetch(PDO::FETCH_ASSOC);
}

// Delete user apply file user_delete.php
function deleteUser($connection, $active, $id)
{
    $sqlDeleteUser = 'UPDATE `users` SET `active` = :active WHERE `id` = :id';

    $objDeleteUser = $connection->prepare($sqlDeleteUser);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $objDeleteUser->execute($data);
}

// Backup user apply file backup.php
function backupUser($connection, $active, $id)
{
    $sql = 'UPDATE `users` SET `active` = :active WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Destroy user apply file user_destroy.php
function destroyUser($connection, $id, $active)
{
    $sql = 'DELETE FROM `users` WHERE `id` = :id AND `active` = :active';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    return $obj->execute($data);
}