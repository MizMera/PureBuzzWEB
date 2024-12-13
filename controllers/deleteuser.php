<?php
header('Content-Type: application/json');
session_start();

try {
    if (!isset($_SESSION['userId']) || !isset($_SESSION['role'])) {
        echo json_encode(["success" => false, "message" => "User not logged in."]);
        exit;
    }

    $currentUserId = $_SESSION['userId'];
    $currentUserRole = $_SESSION['role'];
    $targetUserId = $_POST['id'] ?? $currentUserId;

    // Allow only admins or the user themselves to delete the account
    if ($currentUserRole !== 'admin' && $currentUserId != $targetUserId) {
        echo json_encode(["success" => false, "message" => "Unauthorized action."]);
        exit;
    }

    // Database connection
    $host = "localhost";
    $dbName = "purebuzz";
    $username = "root";
    $passwordDB = "";
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete user
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :userId");
    $stmt->bindParam(':userId', $targetUserId, PDO::PARAM_INT);
    $stmt->execute();

    // Destroy session if the current user deletes their own account
    if ($currentUserId == $targetUserId) {
        session_unset();
        session_destroy();
    }

    echo json_encode(["success" => true, "message" => "Account deleted successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>