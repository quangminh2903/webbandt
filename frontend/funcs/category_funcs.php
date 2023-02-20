<?php
function getCategories($connection, $active)
{
    $sqlSelect = 'SELECT DISTINCT categories.name AS category_name FROM categories INNER JOIN products ON products.category_id = categories.id WHERE categories.active = :active';

    $objSelect = $connection->prepare($sqlSelect);

    $data = [
        ':active' => $active
    ];
    $objSelect->execute($data);

    return $objSelect->fetchAll(PDO::FETCH_ASSOC);
}
