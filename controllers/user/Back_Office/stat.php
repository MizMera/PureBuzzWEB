<?php
header('Content-Type: application/json');

try {
    // Database connection
    $host = "localhost";
    $dbName = "purebuzz_db";
    $username = "root";
    $passwordDB = "";

    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch gender distribution
    $stmtGender = $pdo->prepare("SELECT gender, COUNT(*) as count FROM users GROUP BY gender");
    $stmtGender->execute();
    $genderData = $stmtGender->fetchAll(PDO::FETCH_ASSOC);

    // Fetch roles distribution
    $stmtRoles = $pdo->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $stmtRoles->execute();
    $rolesData = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "genderData" => $genderData,
        "rolesData" => $rolesData
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
