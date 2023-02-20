<?php
// Get all customers apply file customer_list.php
function getAllCustomers($connection, $active)
{
    $sqlSelectAll = 'SELECT * FROM `customers` WHERE `active` != :active ORDER BY `id` DESC';

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Get all customers pagination apply file customer_list.php
function getAllCustomersPagination($connection, $active, $offset, $perPage)
{
    $sqlSelectAll = 'SELECT * FROM `customers` WHERE `active` != :active ORDER BY `id` DESC LIMIT ' . $offset . ', ' . $perPage;

    $objSelectAll = $connection->prepare($sqlSelectAll);

    $data = [
        ':active' => $active
    ];

    $objSelectAll->execute($data);

    return $objSelectAll->fetchAll(PDO::FETCH_ASSOC);
}

// Search customers apply file customer_list.php
function searchCustomers($connection, $keyword, $active, $offset, $perPage)
{
    $sqlSearchCustomers = 'SELECT * FROM `customers` WHERE (`name` LIKE :keyword OR `phone` LIKE :keyword OR `email` LIKE :keyword) AND `active` != :active ORDER BY `id` DESC limit ' . $offset . ', ' . $perPage;

    $objSearchCustomers = $connection->prepare($sqlSearchCustomers);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSearchCustomers->execute($data);

    return $objSearchCustomers->fetchAll(PDO::FETCH_ASSOC);
}

// Get all customers apply file customer_list.php
function countTotalSearchCustomer($connection, $keyword, $active)
{
    $sqlCount = 'SELECT * FROM `customers` WHERE (`name` LIKE :keyword OR `phone` LIKE :keyword OR `email` LIKE :keyword) AND `active` != :active';
    $objSelect = $connection->prepare($sqlCount);

    $data = [
        ':keyword' => '%' . $keyword . '%',
        ':active' => $active
    ];

    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}