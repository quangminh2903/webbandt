<?php
function getProductById($connection, $active, $productId)
{
    $sqlSelect = 'SELECT * FROM `products` WHERE `active` = :active AND `id` = :id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':active' => $active,
        ':id' => $productId
    ];

    $objSelect->execute($data);

    return $objSelect->fetch(PDO::FETCH_ASSOC);
}

function createOrders($connection, $data)
{
    $sqlInsert = 'INSERT INTO `orders`(`customer_name`, `customer_phone`, `customer_email`, `total_money`, `total_products`, `created_date`, `status`) VALUES (?, ?, ?, ?, ?, ?, ?)';

    $objInsert = $connection->prepare($sqlInsert);

    return $objInsert->execute($data);
}

function createOrderItem($connection, $data)
{
    $sqlInsert = 'INSERT INTO `order_items`(`order_id`, `product_id`, `product_name`, `product_image`, `product_price`, `product_quantity`) VALUES (?, ?, ?, ?, ?, ?)';

    $objInsert = $connection->prepare($sqlInsert);

    return $objInsert->execute($data);
}

function getAllOrdersByEmail($connection, $email)
{
    $sqlSelect = 'SELECT * FROM orders WHERE `customer_email` = :email';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':email' => $email
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

function getAllOrdersPagintaionByEmail($connection, $email, $offset, $perPage)
{
    $sqlSelect = 'SELECT * FROM orders WHERE `customer_email` = :email ORDER BY `id` ASC LIMIT ' . $offset . ', ' . $perPage;

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':email' => $email
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

function getInfoOrder($connection, $orderId)
{
    $sqlSelect = 'SELECT * FROM order_items WHERE `order_id` = :order_id';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':order_id' => $orderId
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}

function getAllOrderItemsWithPagination($connection, $orderId, $offset, $perPage)
{
    $sqlSelect = 'SELECT * FROM order_items WHERE `order_id` = :order_id ORDER BY `id` ASC LIMIT ' . $offset . ', ' . $perPage;

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':order_id' => $orderId
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}