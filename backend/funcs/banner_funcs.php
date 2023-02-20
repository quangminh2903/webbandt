<?php
// Get all banners apply file banner_list.php
function getAllBanners($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `banners` WHERE `active` != :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all banners pagination apply file banner_list.php
function getAllBannersPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `banners` WHERE `active` != :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search banners apply file banner_list.php
function searchBanners($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `banners` WHERE `title` LIKE :keyword AND `active` != :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search banner apply file banner_list.php
function countTotalSearchBanner($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `banners` WHERE `title` LIKE :keyword AND `active` != :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get infor Banner apply file banner_backup.php, banner_delete.php, banner_destroy.php
function getInfoBanner($connection, $id)
{
    $sqlSelect = 'SELECT * FROM `banners` WHERE `id` = ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Get infor banner edit apply file banner_edit.php
function getInfoBannerEdit($connection, $id, $active)
{
    $sqlSelect = 'SELECT * FROM `banners` WHERE `id` = ? AND `active` != ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id,
        $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Create banner apply file banner_add.php
function createBanner($connection, $data)
{
    $sqlInsert = 'INSERT INTO `banners`(`title`, `content`, `image_url`, `sort_order`, `active`) VALUES (?, ?, ?, ?, ?)';

    $objInsert = $connection->prepare($sqlInsert);

    return $objInsert->execute($data);
}

// Update banner apply file banner_edit.php
function updateBanner($connection, $data)
{
    $sqlUpdate = 'UPDATE `banners` SET `title` = ?, `content` = ?, `image_url` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Update banner with out image apply file banner_edit.php
function updateBannerWithOutImage($connection, $data)
{
    $sqlUpdate = 'UPDATE `banners` SET `title` = ?, `content` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Delete image banner upload apply file banner_destroy.php, banner_edit.php
function deleteImageBannerUpload($connection, $id)
{
    $sql = 'SELECT `image_url` FROM `banners` WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id
    ];

    $obj->execute($data);

    $result = $obj->fetch(PDO::FETCH_ASSOC);

    if ($result['image_url'] != '' && file_exists('../../../common/uploads/banners/' . $result['image_url'])) {
        unlink("../../../common/uploads/banners/" . $result['image_url']);
    }
}

// Delete banner apply file banner_delete.php
function deleteBanner($connection, $id, $active)
{
    $sqlDeleteBanner = 'UPDATE `banners` SET `active` = :active WHERE `id` = :id';

    $objDeleteBanner = $connection->prepare($sqlDeleteBanner);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $objDeleteBanner->execute($data);
}

// Get all backup banners apply file banner_backup_list.php
function getAllBackupBanners($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `banners` WHERE `active` = :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search backup banners apply file banner_backup_list.php
function searchBackupBanner($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `banners` WHERE `title` LIKE :keyword AND `active` = :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Get all backup banner pagination apply file banner_backup_list.php
function getAllBackupBannerPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `banners` WHERE `active` = :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search backup banner apply file banner_backup_list.php
function countTotalSearchBackupBanner($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `banners` WHERE `title` LIKE :keyword AND `active` != :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Backup banner apply file banner_backup.php
function backupBanner($connection, $active, $id)
{
    $sql = 'UPDATE `banners` SET `active` = :active WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Destroy banner apply file banner_destroy.php
function destroyBanner($connection, $id, $active)
{
    $sql = 'DELETE FROM `banners` WHERE `id` = :id AND `active` = :active';
    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    return $obj->execute($data);
}