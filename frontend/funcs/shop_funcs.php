<?php
function getAllCategories($connection, $data)
{
    $sqlSelectAll = 'SELECT * FROM `categories` WHERE `active` = ? ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProducts($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProductsPagination($connection, $offsets, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = 1 ORDER BY `id` ASC LIMIT ' . $offsets . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $objSelectAll->execute();

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function searchProducts($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.name LIKE :keyword AND products.active = :active ORDER BY products.id LIMIT ' . $offset . ', ' . $perPage;

    $objSearchUsers = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearchUsers->execute($data);

    return $objSearchUsers->fetchAll(PDO::FETCH_ASSOC);
}

function getIdCategories($connection, $name)
{
    $sqlSelectAll = 'SELECT * FROM `categories` WHERE `name` = :name';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':name' => $name
    ];
    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProductByCategory($connection, $categoryName, $active, $offset, $perPage)
{
    $sqlSearch = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE categories.name = :category_name AND products.active = :active ORDER BY products.id ASC LIMIT ' . $offset . ', ' . $perPage;

    $objSearchUsers = $connection->prepare($sqlSearch);

    $data = [
        ':category_name' => $categoryName,
        ':active' => $active
    ];

    $objSearchUsers->execute($data);

    return $objSearchUsers->fetchAll(PDO::FETCH_ASSOC);
}


function getSellProduct($connection, $isBestSell, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active AND `is_best_sell` = :isBestSell ORDER BY `id` ASC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':isBestSell' => $isBestSell,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getNewProduct($connection, $isNew, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active AND `is_new` = :isnew ORDER BY `id` ASC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':isnew' => $isNew,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function tagProducts($connection, $keyword, $active, $offset, $perPage)
{
    $sqlTag = 'SELECT products.*, categories.name AS category_name, brands.name AS brand_name FROM products INNER JOIN categories INNER JOIN brands ON products.category_id = categories.id AND products.brand_id = brands.id WHERE products.tags LIKE :keyword AND products.active = :active ORDER BY products.id LIMIT ' . $offset . ', ' . $perPage;

    $objTag = $connection->prepare($sqlTag);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objTag->execute($data);

    return $objTag->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProductsViewed($connection, $viewedId, $offset, $perPage)
{
    $sqlSelectAll = "SELECT * FROM products WHERE id IN ($viewedId) ORDER BY RAND() LIMIT $offset, $perPage";

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $objSelectAll->execute();

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}