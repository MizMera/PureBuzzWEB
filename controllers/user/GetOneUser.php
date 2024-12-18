<?php
header('Content-Type: application/json');
session_start();

try {
    // Check if the user is logged in
    if (!isset($_SESSION['userId'])) {
        echo json_encode([
            "success" => false,
            "message" => "User not logged in."
        ]);
        exit;
    }

    // Get the logged-in user ID from the session
    $userId = $_SESSION['userId'];

    // Database configuration
    $host = "localhost";
    $dbName = "purebuzz_db";
    $username = "root";
    $passwordDB = "";

    // Connect to the database
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the user's details
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, date_of_birth, mobile, gender, role, location FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode([
            "success" => true,
            "data" => $user
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "User not found."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>