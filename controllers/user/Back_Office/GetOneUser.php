<?php
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    try {
        // Database connection details
        $host = "localhost";
        $dbName = "purebuzz_db";
        $username = "root";
        $passwordDB = "";

        // PDO connection
        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch user details
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");//affiche id du user dans tab users
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Return user details as JSON
            echo json_encode($user);
        } else {
            echo json_encode(null);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "No user ID provided."]);
}
?>
