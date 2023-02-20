<?php
// Get all brand apply brand_list.php
function getAllBrands($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `brands` WHERE `active` != :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all brands pagination apply file brand_list.php
function getAllBrandsPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `brands` WHERE `active` != :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search brands apply brand_list.php
function searchBrands($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `brands` WHERE `name` LIKE :keyword AND `active` != :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Count total brand search apply file brand_list.php
function countTotalBrandSearch($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `brands` WHERE `name` LIKE :keyword AND `active` != :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];
    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get infor brand apply file brand_destroy.php, brand_backup.php, brand_delete.php
function getInfoBrand($connection, $id)
{
    $sqlSelect = 'SELECT * FROM `brands` WHERE `id` = ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Get infor brand edit apply file brand_edit.php
function getInfoBrandEdit($connection, $id, $active)
{
    $sqlSelect = 'SELECT * FROM `brands` WHERE `id` = ? and `active` != ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id,
        $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Update brand apply file brand_edit.php
function updateBrand($connection, $data)
{
    $sqlUpdate = 'UPDATE `brands` SET `name` = ?, `image_url` = ?, `link` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Update brand with out image apply brand_edit.php
function updateBrandWithOutImage($connection, $data)
{
    $sqlUpdate = 'UPDATE `brands` SET `link` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Create brand apply file brand_add.php
function createBrand($connection, $data)
{
    $sqlInsert = 'INSERT INTO `brands`(`name`, `image_url`, `link`, `sort_order`, `active`) VALUES (?, ?, ?, ?, ?)';

    $objInsert = $connection->prepare($sqlInsert);

    return $objInsert->execute($data);
}

// Delete image upload apply file brand_edit.php, brand_destroy.php
function deleteImageBrandUpload($connection, $id)
{
    $sql = 'SELECT `image_url` FROM `brands` WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id
    ];

    $obj->execute($data);

    $result = $obj->fetch(PDO::FETCH_ASSOC);

    if ($result['image_url'] != '' && file_exists('../../../common/uploads/brands/' . $result['image_url'])) {
        unlink("../../../common/uploads/brands/" . $result['image_url']);
    }
}

// Delete brand apply file brand_delete.php
function deleteBrand($connection, $id, $active)
{
    $sqlDelete = 'UPDATE `brands` SET `active` = :active WHERE `id` = :id';

    $objDelete = $connection->prepare($sqlDelete);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $objDelete->execute($data);
}

function getAllBackupBrand($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `brands` WHERE `active` = :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search backup brand apply file brand_backup_list.php
function searchBackupBrand($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `brands` WHERE `name` LIKE :keyword AND `active` = :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Get all backup brand pagination apply file brand_backup_list.php
function getAllBackupBrandPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `brands` WHERE `active` = :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search backup brand apply file brand_backup_list.php
function countTotalSearchBackupBrand($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `brands` WHERE `name` LIKE :keyword AND `active` != :active';
    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Backup brand apply brand_backup.php
function backupBrand($connection, $active, $id)
{
    $sql = 'UPDATE `brands` SET `active` = :active WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Destroy brand apply file brand_destroy.php
function destroyBrand($connection, $id, $active)
{
    $sql = 'DELETE FROM `brands` WHERE `id` = :id AND `active` = :active';
    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    return $obj->execute($data);
}

// Check product brand apply file brand_delete.php and brand_destroy.php
function checkProductsBrand($connection, $brandId) {
    $sqlSelect = 'SELECT * FROM products WHERE brand_id = :brand_id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':brand_id' => $brandId
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}