<?php
$dsn = "mysql:host=localhost;dbname=shop";
$user = "root";
$pass = '';
$option = [
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
];

try {
    $conn = new PDO($dsn, $user, $pass, $option);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Failed to connect ' . $e->getMessage();
}
