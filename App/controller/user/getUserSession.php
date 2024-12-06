<?php
header('Content-Type: application/json');
session_start();

if (isset($_SESSION['userId'])) {
    echo json_encode([
        "success" => true,
        "data" => [
            "first_name" => $_SESSION['first_name'], // Assuming `first_name` is stored in the session
            "last_name" => $_SESSION['last_name'],
            "email" => $_SESSION['email'],
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in."
    ]);
}
?>
