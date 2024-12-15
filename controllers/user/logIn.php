<?php
//l'en-tête de la réponse HTTP comme étant du JSON, ce qui indique que le script retournera des données JSON au lieu de HTML
header('Content-Type: application/json');

try {
    // Vérifie si la méthode HTTP de la requête est POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
        exit;
    }

    // Database configuration
    $host = "localhost";
    $dbName = "purebuzz_db";
    $username = "root";
    $passwordDB = "";

    // Connect to the database
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // récupèrtion les données email et password envoyées dans la requête POST
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');// trim() supprime les espaces au début et à la fin des valeurs.

    // email and password vide ou non
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        exit;
    }

    // Check if the user exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
//si aucune ligne n'est trouvée dans la base de données (c'est-à-dire que l'utilisateur n'existe pas)
    if ($stmt->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    // Récupère les données de l'utilisateur trouvé dans la base de données sous forme de tableau
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if (!password_verify($password, $user['password'])) {
        echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        exit;
    }

    // Start the session and set all user details
    session_start();
    $_SESSION['userId'] = $user['id'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['date_of_birth'] = $user['date_of_birth'];
    $_SESSION['location'] = $user['location'];
    $_SESSION['mobile'] = $user['mobile'];
    $_SESSION['gender'] = $user['gender'];
    $_SESSION['role'] = $user['role'];

    // Return success response si la connexion est réussie
    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "user" => [
            "id" => $user['id'],
            "first_name" => $user['first_name'],
            "last_name" => $user['last_name'],
            "email" => $user['email'],
            "date_of_birth" => $user['date_of_birth'],
            "location" => $user['location'],
            "mobile" => $user['mobile'],
            "gender" => $user['gender'],
            "role" => $user['role']
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>