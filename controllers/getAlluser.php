<?php
header('Content-Type: application/json');
session_start();

try {
    // Check if the user is logged in
    if (!isset($_SESSION['userId']) || !isset($_SESSION['role'])) {
        echo json_encode([
            "success" => false,
            "message" => "User not logged in or role not defined."
        ]);
        exit;
    }

    // Check if the user role is admin
    if ($_SESSION['role'] !== 'admin') {
        echo json_encode([
            "success" => false,
            "message" => "Access denied. Admin privileges are required."
        ]);
        exit;
    }

    // Database configuration
    $host = "localhost";
    $dbName = "purebuzz";
    $username = "root";
    $passwordDB = "";

    // Connect to the database
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all users
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email,profile_picture, date_of_birth, role FROM users");
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $users
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
?>