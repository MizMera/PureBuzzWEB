<?php
// public/index.php

// Include necessary files
include_once '../config/database.php';
include_once '../controllers/ClaimController.php';

// Create a database connection
$database = new Database();
$db = $database->getConnection();

// Create a controller instance
$controller = new ClaimController($db);

// Routing logic based on query parameters
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'addClaim' && $_SERVER['REQUEST_METHOD'] == 'POST') {
        // Make sure required POST data exists
        if (isset($_POST['claimant_name'], $_POST['description'], $_POST['status'])) {
            // Call the addClaim method in ClaimController
            $controller->addClaim($_POST['claimant_name'], $_POST['description'], $_POST['status']);
        } else {
            echo "Missing required form fields.";
        }
    }
} else {
    // Default action to list all claims
    $controller->listClaims();
}
?>
