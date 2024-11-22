<?php
// config/database.php

$host = 'localhost';  // Database host
$db = 'omar';  // Your database name
$user = 'root';  // Your database username
$pass = '';  // Your database password
$charset = 'utf8mb4';  // Charset to use for the connection

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);  // Establish the PDO connection
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
