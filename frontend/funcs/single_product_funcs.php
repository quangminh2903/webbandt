<?php
function getInfoProduct($connection, $id)
{

    $sqlSelect = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name, product_images.image_url AS prod_image FROM products INNER JOIN categories INNER JOIN brands INNER JOIN product_images ON products.category_id = categories.id AND products.brand_id = brands.id  WHERE products.id = ?';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        $id
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

function getGallery($connection, $id, $active)
{
    $sqlSelect = 'SELECT * FROM product_images INNER JOIN products ON product_images.product_id = products.id WHERE product_images.product_id = :id AND product_images.active = :active';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':id' => $id,
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProduct($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active ORDER BY RAND() DESC LIMIT 4';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getProductTogether($connection, $active, $categoryId, $productId)
{
    $sqlSelect = 'SELECT * FROM `products` WHERE `active` = :active AND `category_id` = :category_id AND `id` != :productId ORDER BY RAND() DESC';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':active' => $active,
        ':category_id' => $categoryId,
        ':productId' => $productId
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProducts($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active ORDER BY RAND() ASC LIMIT 3';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProductsViewed($connection, $cookieId, $active)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `id` = :cookieId AND `active` = :active ORDER BY `id` ASC LIMIT 3';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':cookieId' => $cookieId,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllRelatedProducts($connection, $active) {
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active ORDER BY RAND() LIMIT 2';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}