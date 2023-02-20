<?php
// Get all categories apply file category_list.php
function getAllCategories($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `categories` WHERE `active` != :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all categories pagination apply file category_list.php
function getAllCategoriesPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `categories` WHERE `active` != :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search categories apply file category_list.php
function searchCategories($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `categories` WHERE `name` LIKE :keyword AND `active` != :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search apply file category_list.php
function countTotalSearchCategories($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `categories` WHERE `name` LIKE :keyword AND `active` != :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get infor categories apply file category_delete.php, category_backup.php and category_destroy.php
function getInfoCategories($connection, $id)
{
    $sqlSelect = 'SELECT * FROM `categories` WHERE `id` = ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Create category apply file category_add.php
function createCategory($connection, $data)
{
    $sqlInsert = 'INSERT INTO `categories`(`name`, `sort_order`, `active`) VALUES (?, ?, ?)';

    $objInsert = $connection->prepare($sqlInsert);

    return $objInsert->execute($data);
}

// Get infor categories edit apply file category_edit.php
function getInfoCategoriesEdit($connection, $id, $active)
{
    $sqlSelect = 'SELECT * FROM `categories` WHERE `id` = ? and `active` != ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id,
        $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Update categories apply file category_edit.php
function updateCategories($connection, $data)
{
    $sqlUpdate = 'UPDATE `categories` SET `name` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Delete category apply file category_delete.php
function deleteCategories($connection, $active, $id)
{
    $sqlDelete = 'UPDATE `categories` SET `active` = :active WHERE `id` = :id';

    $objDelete = $connection->prepare($sqlDelete);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $objDelete->execute($data);
}

// Get all deleted category apply file category_backup_list.php
function getAllDeletedCategory($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `categories` WHERE `active` = :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search deleted categories apply file category_backup_list.php
function searchDeletedCategories($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `categories` WHERE `name` LIKE :keyword AND `active` = :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Get all deleted category pagination apply file category_backup_list.php
function getAllDeletedCategoryPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `categories` WHERE `active` = :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];
    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search deleted category apply file category_backup_list.php
function countTotalSearchDeletedCategory($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `categories` WHERE `name` LIKE :keyword AND `active` = :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Backup category apply file category_backup.php
function backupCategory($connection, $active, $id)
{
    $sql = 'UPDATE `categories` SET `active` = :active WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Destroy category apply file category_destroy.php
function destroyCategory($connection, $active, $id)
{
    $sql = 'DELETE FROM `categories` WHERE `id` = :id AND `active` = :active';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Check product category apply file category_delete.php and category_destroy.php
function checkProductCategory($connection, $categoryId) {
    $sqlSelect = 'SELECT * FROM products WHERE category_id = :category_id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':category_id' => $categoryId
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}