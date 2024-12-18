<?php
session_start();
include_once __DIR__ . '/../../../config/database.php';

// Connexion à la base de données via PDO
$pdo = Database::getConnexion();
// Verify the request type
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'remove' && isset($_POST['product_id'])) {
        $productId = $_POST['product_id'];

        // Remove product from the cart
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
    } elseif ($action == 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Update the quantity of the product in the cart
        if ($quantity > 0) {
            $_SESSION['cart'][$productId] = $quantity;
        }
    }
}
?>
