<?php
// Get all orders apply file order_list.php
function getAllOrders($connection)
{
    $sqlSelectAll = 'SELECT * FROM `orders` ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $objSelectAll->execute();

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search orders apply file order_list.php
function searchOrders($connection, $keyword, $offset, $perPage)
{
    $sqlSearch = 'SELECT * FROM `orders` WHERE (`customer_name` LIKE :keyword OR `customer_phone` LIKE :keyword OR `customer_email` LIKE :keyword) ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearch = $connection->prepare($sqlSearch);

    $data = [
        ':keyword' => '%' . $keyword . '%',
    ];

    $objSearch->execute($data);

    return $objSearch->fetchAll(PDO::FETCH_ASSOC);
}

// Get all orders pagination apply file order_list.php
function getAllOrdersPagination($connection, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `orders` ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $objSelectAll->execute();

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Count total search orders apply file order_list.php
function countTotalSearchOrders($connection, $keyword)
{
    $sqlCount = 'SELECT * FROM `orders` WHERE (`customer_name` LIKE :keyword OR `customer_phone` LIKE :keyword OR `customer_email` LIKE :keyword)';
    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get infor orders apply file order_items_list.php and order_action.php
function getInfoOrders($connection, $orderId)
{
    $sqlSelect = 'SELECT * FROM order_items WHERE `order_id` = :order_id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':order_id' => $orderId
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

// Get all order detail apply file order_items_list.php
function getAllOrderDetail($connection, $orderId)
{
    $sqlSelectAll = 'SELECT * FROM `order_items` WHERE `order_id` = :order_id ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':order_id' => $orderId
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Action order apply file order_action.php
function actionOrder($connection, $status, $id)
{
    $sqlUpdate = 'UPDATE `orders` SET `status` = :status WHERE `id` = :id';

    $objUpdate = $connection->prepare($sqlUpdate);

    $data = [
        ':status' => $status,
        ':id' => $id
    ];

    return $objUpdate->execute($data);
}