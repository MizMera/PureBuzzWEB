<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $token = $_GET["token"] ?? null;

    if (!$token) {
        die("Invalid verification link.");
    }

    try {
        // Database connection
        $host = "localhost";
        $dbName = "purebuzz";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Find the user with the provided token
        $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = :token AND status = 'pending'");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Update the user's status to 'active'
            $updateStmt = $pdo->prepare("UPDATE users SET status = 'active', verification_token = NULL WHERE verification_token = :token");
            $updateStmt->bindParam(':token', $token);
            $updateStmt->execute();

            echo "Email verified successfully! You can now log in.";
        } else {
            echo "Invalid or expired verification link.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>