<?php
// le contenu renvoyé par le serveur est au format JSON.
header('Content-Type: application/json');

try {
    // Database connection
    $host = "localhost";
    $dbName = "purebuzz";
    $username = "root";
    $passwordDB = "";

    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $passwordDB);
    // configurer le mode de gestion des erreurs pour l'objet PDO.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//PDO:PHP Data Objects

    // prépare une requête SQL pour obtenir la distribution des genres.
    $stmtGender = $pdo->prepare("SELECT gender, COUNT(*) as count FROM users GROUP BY gender");
    $stmtGender->execute();
    //cette ligne récupère tous les résultats sous forme d'un tableau associatif
    $genderData = $stmtGender->fetchAll(PDO::FETCH_ASSOC);

    // de meme pour role
    $stmtRoles = $pdo->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $stmtRoles->execute();
    $rolesData = $stmtRoles->fetchAll(PDO::FETCH_ASSOC);

// conversion des données PHP en un format JSON
    echo json_encode([
        "success" => true,
        "genderData" => $genderData,
        "rolesData" => $rolesData
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
