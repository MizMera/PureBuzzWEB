<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

if (isset($_POST["id"])) {
    $userId = $_POST["id"];
    $firstName = $_POST["first_name"] ?? null;
    $lastName = $_POST["last_name"] ?? null;
    $dateOfBirth = $_POST["date_of_birth"] ?? null;
    $email = $_POST["email"] ?? null;
    $mobile = $_POST["mobile"] ?? null;
    $gender = $_POST["gender"] ?? null;
    $role = $_POST["role"] ?? null;
    $location = $_POST["location"] ?? null;

    if (!$firstName || !$lastName || !$email) {
        echo json_encode(["success" => false, "message" => "First name, last name, and email are required."]);
        exit;
    }

    try {
        // Database connection
        $host = "localhost";
        $dbName = "purebuzz_db";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare the update query
        $stmt = $pdo->prepare("
            UPDATE users SET 
                first_name = :first_name,
                last_name = :last_name,
                date_of_birth = :date_of_birth,
                email = :email,
                mobile = :mobile,
                gender = :gender,
                role = :role,
                location = :location
            WHERE id = :id
        ");

        // Bind parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':date_of_birth', $dateOfBirth);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User updated successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update user."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User ID is required."]);
}
?>
