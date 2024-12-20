<?php
header('Content-Type: application/json');
session_start();

try {
    // Check if the user is logged in and has admin privileges
    if (!isset($_SESSION['userId']) || $_SESSION['role'] !== 'admin') {
        echo json_encode([
            "success" => false,
            "message" => "Access denied. Admin privileges required."
        ]);
        exit;
    }

    // Database configuration
    $host = "localhost";
    $dbName = "purebuzz_db3";
    $username = "root";
    $passwordDB = "";

    // Connect to the database
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete the user from the database
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $_POST['id'], PDO::PARAM_INT); // Use 'id' from POST
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "User deleted successfully."
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>
