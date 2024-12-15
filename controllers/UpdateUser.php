<?php
header('Content-Type: application/json');
session_start();

try {
    if (!isset($_SESSION['userId'])) {
        echo json_encode(["success" => false, "message" => "User not logged in."]);
        exit;
    }

    $currentUserId = $_SESSION['userId'];
    $targetUserId = $_POST['id'] ?? $currentUserId;

    // Database connection
    $host = "localhost";
    $dbName = "purebuzz";
    $username = "root";
    $passwordDB = "";
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch current user's role
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :currentUserId");
    $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_INT);
    $stmt->execute();
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$currentUser) {
        echo json_encode(["success" => false, "message" => "Unauthorized action."]);
        exit;
    }

    $currentUserRole = $currentUser['role'];

    // Allow only admins or the user themselves to update details
    if ($currentUserRole !== 'admin' && $currentUserId != $targetUserId) {
        echo json_encode(["success" => false, "message" => "Unauthorized action."]);
        exit;
    }

    // Update user details
    $stmt = $pdo->prepare("
        UPDATE users SET 
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            date_of_birth = :date_of_birth,
            mobile = :mobile,
            gender = :gender,
            role = :role,
            location = :location
        WHERE id = :userId
    ");
    $stmt->bindParam(':first_name', $_POST['first_name']);
    $stmt->bindParam(':last_name', $_POST['last_name']);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':date_of_birth', $_POST['date_of_birth']);
    $stmt->bindParam(':mobile', $_POST['mobile']);
    $stmt->bindParam(':gender', $_POST['gender']);
    $stmt->bindParam(':role', $_POST['role']);
    $stmt->bindParam(':location', $_POST['location']);
    $stmt->bindParam(':userId', $targetUserId, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(["success" => true, "message" => "User updated successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>