<?php
//le contenu de la réponse sera au format JSON
header('Content-Type: application/json');
session_start();
//vérifie si la variable de session userId est définie
if (!isset($_SESSION['userId'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in."
    ]);
    exit();
}

echo json_encode([
    "success" => true,
    "message" => "Session active.",
    "userId" => $_SESSION['userId'],
    "role" => $_SESSION['role']
]);
?>