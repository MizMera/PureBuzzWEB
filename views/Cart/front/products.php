<?php
session_start();
include_once 'config.php'; // Connexion à la base de données

// Obtenez la connexion PDO à partir de la classe Config
$pdo = Config::getConnexion();

// Afficher les produits disponibles à l'achat
$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll();

// Afficher les produits
echo "<h2>Produits</h2>";
foreach ($products as $product) {
    echo "<p>{$product['name']} - {$product['price']}€ <a href='add_to_cart.php?add_to_cart={$product['id']}'>Ajouter au panier</a></p>";
}
?>
