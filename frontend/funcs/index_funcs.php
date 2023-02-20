<?php
function getAllBanners($connection, $active) {
    $sqlSelectAll = 'SELECT * FROM `banners` WHERE `active` = :active ORDER BY `id` ASC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllNewProduct($connection, $active) {
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active ORDER BY `id` DESC LIMIT 8';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllBrands($connection, $active) {
    $sqlSelectAll = 'SELECT * FROM `brands` WHERE `active` = :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getNewProduct($connection, $isNew, $active) {
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active AND `is_new` = :isnew ORDER BY RAND() ASC LIMIT 3' ;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':isnew' => $isNew,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getSellProduct($connection, $isBestSell, $active) {
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `active` = :active AND `is_best_sell` = :isBestSell ORDER BY RAND() DESC LIMIT 3' ;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':isBestSell' => $isBestSell,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProductsViewd($connection, $cookieId, $active)
{
    $sqlSelectAll = 'SELECT * FROM `products` WHERE `id` = :cookieId AND `active` = :active ORDER BY `id` DESC LIMIT 3';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':cookieId' => $cookieId,
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}
