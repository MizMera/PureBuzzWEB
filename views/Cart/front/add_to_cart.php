<?php
session_start();
include_once __DIR__ . '/../../../config/database.php';

// Connexion à la base de données via PDO
$pdo = Database::getConnexion();

// Ajouter un produit au panier
if (isset($_GET['add_to_cart'])) {
    $productId = $_GET['add_to_cart'];

    // Si le panier n'existe pas, le créer
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Vérifier si le produit est déjà dans le panier
    if (!isset($_SESSION['cart'][$productId])) {
        // Ajouter le produit avec une quantité de 1
        $_SESSION['cart'][$productId] = 1;
    }
}