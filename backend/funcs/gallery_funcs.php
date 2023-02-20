<?php
// Get all gallery by id apply file gallery_list.php
function getAllGalleryById($connection, $productId, $active)
{
    $sqlSelectAll = 'SELECT `image_url`, `sort_order` FROM `product_images` WHERE `product_id` = :product_id AND active != :active  ORDER BY id DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':product_id' => $productId,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all gallery pagination apply file gallery_list.php
function getAllGalleryPagination($connection, $productId, $active, $offsets, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `product_images` WHERE `product_id` = :product_id AND active != :active  ORDER BY id DESC LIMIT ' . $offsets . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':product_id' => $productId,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Delete gallery apply file gallery_delete.php
function deleteGalleryById($connection, $id, $active)
{
    $sqlDelete = 'UPDATE `product_images` SET `active` = :active WHERE `id` = :id';

    $objDelete = $connection->prepare($sqlDelete);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $objDelete->execute($data);
}

// Get infor gallery by id product apply file gallery-backup.php, gallery_delete.php, gallery_destroy.php
function getInfoGalleryById($connection, $id)
{
    $sqlSelect = 'SELECT * FROM `product_images` WHERE `id` = :id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':id' => $id
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Get all backup gallery by id product apply file gallery_backup_list.php
function getAllBackupGalleryById($connection, $productId, $active)
{
    $sqlSelectAll = 'SELECT `image_url`, `sort_order` FROM `product_images` WHERE `product_id` = :product_id AND active = :active  ORDER BY id DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':product_id' => $productId,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all backup gallery pagination apply file gallery_backup_list.php
function getAllBackupGalleryPagination($connection, $productId, $active, $offsets, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `product_images` WHERE `product_id` = :product_id AND active = :active  ORDER BY id DESC LIMIT ' . $offsets . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':product_id' => $productId,
        ':active' => $active
    ];
    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Backup gallery apply file gallery_backup.php
function backupGallery($connection, $active, $id)
{
    $sql = 'UPDATE `product_images` SET `active` = :active WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Destroy gallery apply file gallery_destroy.php
function destroyGallery($connection, $id, $active)
{
    $sql = 'DELETE FROM `product_images` WHERE `id` = :id AND `active` = :active';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    return $obj->execute($data);
}

// Create gallery apply file gallery_add.php
function createGallery($connection, $data)
{
    $sqlInsert = 'INSERT INTO `product_images`(`product_id`, `image_url`, `sort_order`, `active`) VALUES (?, ?, ?, ?)';

    $objInsert = $connection->prepare($sqlInsert);

    return $objInsert->execute($data);
}

// Get infor gallery edit apply file gallery_edit.php
function getInfoGalleryEdit($connection, $id, $active)
{
    $sqlSelect = 'SELECT * FROM `product_images` WHERE `id` = :id AND `active` != :active';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Update gallery apply file gallery_edit.php
function updateGallery($connection, $data)
{
    $sqlUpdate = 'UPDATE `product_images` SET `image_url` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Update gallery with out img apply file gallery_edit.php
function updateGalleryWithOutImage($connection, $data)
{
    $sqlUpdate = 'UPDATE `product_images` SET `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Delete image gallery upload apply file gallery_destroy.php, gallery_edit.php
function deleteImageGalleryUpload($connection, $id)
{
    $sql = 'SELECT `image_url` FROM `product_images` WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id
    ];

    $obj->execute($data);

    $result = $obj->fetch(PDO::FETCH_ASSOC);

    if ($result['image_url'] != '' && file_exists('../../../common/uploads/galleries/' . $result['image_url'])) {
        unlink("../../../common/uploads/galleries/" . $result['image_url']);
    }
}