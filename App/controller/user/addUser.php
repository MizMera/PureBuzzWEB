<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $dateOfBirth = $_POST["date_of_birth"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $location = $_POST["location"];
    $mobile = $_POST["mobile"];
    $gender = $_POST["gender"];
    $role = $_POST["role"];

    try {
        $host = "localhost";
        $dbName = "purebuzz";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, date_of_birth, email, password, location, mobile, gender, role)
             VALUES (:first_name, :last_name, :date_of_birth, :email, :password, :location, :mobile, :gender, :role)"
        );

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':date_of_birth', $dateOfBirth);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':role', $role);

        $stmt->execute();

        // Return JSON response
        echo json_encode([
            "success" => true,
            "message" => "User added successfully!"
        ]);
    } catch (PDOException $e) {
        // Return JSON error response
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
}
?>
