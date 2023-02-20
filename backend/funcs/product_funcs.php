<?php
// Get name category apply file product_add.php, product_edit.php
function getNameCategory($connection, $active)
{
    $sqlSelect = 'SELECT `name`, `id` FROM `categories` WHERE `active` = :active';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get name brand apply file product_add.php, product_edit.php
function getNameBrand($connection, $active)
{
    $sqlSelect = 'SELECT `name`, `id` FROM `brands` WHERE `active` = :active';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Create product apply file product_add.php
function createProduct($connection, $data)
{
    $sqlInsertProduct = 'INSERT INTO `products`(`category_id`, `brand_id`,`name`, `image`, `price`, `description`, `tags`, `is_best_sell`, `is_new`,`sort_order`, `active`) VALUES (?, ?, ? ,? ,? ,? ,? ,? ,? ,? ,?)';

    $objInsertProduct = $connection->prepare($sqlInsertProduct);

    return $objInsertProduct->execute($data);
}

// Get all products apply file product_list.php
function getAllProducts($connection, $activeProduct)
{
    $sqlSelectAll = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.active != :active_product ORDER BY products.id DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active_product' => $activeProduct
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all products pagination apply file product_list.php
function getAllProductsPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.active != :active ORDER BY products.id DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];
    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search products apply file product_list.php
function searchProducts($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearchUsers = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE (categories.name LIKE :keyword OR brands.name LIKE :keyword OR products.name LIKE :keyword OR products.tags LIKE :keyword) AND products.active != :active ORDER BY products.id LIMIT ' . $offset . ', ' . $perPage;

    $objSearchUsers = $connection->prepare($sqlSearchUsers);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearchUsers->execute($data);

    return $objSearchUsers->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search product apply file product_list.php
function countTotalSearchProduct($connection, $keyword, $active)
{
    $sqlCount = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE (categories.name LIKE :keyword OR brands.name LIKE :keyword OR products.name LIKE :keyword OR products.tags LIKE :keyword) AND products.active != :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search backup product apply file product_backup_list.php
function countTotalSearchBackupProduct($connection, $keyword, $active)
{
    $sqlCount = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE (categories.name LIKE :keyword OR brands.name LIKE :keyword OR products.name LIKE :keyword OR products.tags LIKE :keyword) AND products.active = :active';

    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get infor product apply file product_detail.php, product_backup.php, product_delete.php, product_destroy.php, gallery_add.php, gallery_backup_list.php, gallery_backup.php, gallery_delete.php, gallery_list.php, gallery_edit.php
function getInfoProduct($connection, $id) {
    $sqlSelect = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.id = :product_id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':product_id' => $id
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Get infor product edit apply file product_edit.php
function getInfoProductEdit($connection, $id, $active) {
    $sqlSelect = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.id = ? AND products.active != ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id,
        $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

// Delete product apply file product_delete.php
function deleteProduct($connection, $active, $id, $isBestSell, $isNew) {
    $sqlDelete = 'UPDATE `products` SET `active` = :active, `is_best_sell` = :is_best_sell, `is_new` = :is_new WHERE `id` = :id';

    $objDelete = $connection->prepare($sqlDelete);

    $data = [
        ':active' => $active,
        ':id' => $id,
        ':is_best_sell' => $isBestSell,
        ':is_new' => $isNew
    ];

    return $objDelete->execute($data);
}

// Get all backup product apply file product_backup_list.php
function getAllBackupProduct($connection, $activeProduct)
{
    $sqlSelectAll = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.active = :active_product ORDER BY products.id DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active_product' => $activeProduct
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search backup product apply file product_backup_list.php
function searchBackupProduct($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE (categories.name LIKE :keyword OR brands.name LIKE :keyword OR products.name LIKE :keyword OR products.tags LIKE :keyword) AND products.active = :active ORDER BY products.id LIMIT ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];
    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Get all backup product pagination apply file product_backup_list.php
function getAllBackupProductPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.active = :active ORDER BY products.id DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];
    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Update product apply file product_edit.php
function updateProduct($connection, $data)
{
    $sqlUpdate = 'UPDATE `products` SET `category_id` = ?, `brand_id` = ?, `name` = ?, `image` = ?, `price` = ?, `old_price` = ?, `description` = ?, `tags` = ?, `is_best_sell` = ?, `is_new` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdate = $connection->prepare($sqlUpdate);

    return $objUpdate->execute($data);
}

// Update product with out img apply file product_edit.php
function updateProductWithOutImg($connection, $data)
{
    $sqlUpdateProduct = 'UPDATE `products` SET `category_id` = ?, `brand_id` = ?, `name` = ?, `price` = ?, `old_price` = ?, `description` = ?, `tags` = ?, `is_best_sell` = ?, `is_new` = ?, `sort_order` = ?, `active` = ? WHERE `id` = ?';

    $objUpdateProduct = $connection->prepare($sqlUpdateProduct);

    return $objUpdateProduct->execute($data);
}

// Backup product apply file product_backup.php, product_delete.php
function backupProduct($connection, $active, $id) {
    $sql = 'UPDATE `products` SET `active` = :active WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':active' => $active,
        ':id' => $id
    ];

    return $obj->execute($data);
}

// Destroy product apply file product_destroy.php
function destroyProduct($connection, $id, $active) {
    $sql = 'DELETE FROM `products` WHERE `id` = :id AND `active` = :active';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    return $obj->execute($data);
}

// Delete image product upload apply file product_destroy.php, product_edit.php
function deleteImageProductUpload($connection, $id)
{
    $sql = 'SELECT `image` FROM `products` WHERE `id` = :id';

    $obj = $connection->prepare($sql);

    $data = [
        ':id' => $id
    ];

    $obj->execute($data);

    $result = $obj->fetch(PDO::FETCH_ASSOC);

    if ($result['image'] != '' && file_exists('../../../common/uploads/products/' . $result['image'])) {
        unlink("../../../common/uploads/products/" . $result['image']);
    }
}
?>