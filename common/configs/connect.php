<?php
    const DB_DSN = 'mysql:host=localhost;dbname=sell_phones;port=3306;charset=utf8';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';

    try {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        $connection = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD, $options);
    } catch (PDOException $exception) {
        echo 'Lỗi kết nối: ' . $exception->getMessage();
        die();
    }
?>