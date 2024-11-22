<?php
session_start();
include_once 'config.php'; // Inclure la configuration de la base de données

// Récupérer la connexion PDO via la classe Config
$pdo = Config::getConnexion();

// Vérifier si le panier est vide
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Votre panier est vide.";
    exit();
}

// Commencer la transaction
$pdo->beginTransaction();

try {
    // Ajouter la commande dans la table cart
    $stmt = $pdo->prepare("INSERT INTO cart (total) VALUES (?)");
    $totalPanier = 0;

    // Calculer le total du panier
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Récupérer le prix du produit
        $stmt_product = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt_product->execute([$product_id]);
        $product = $stmt_product->fetch();
        
        if ($product) {
            $totalPanier += $product['price'] * $quantity;
        }
    }

    // Insérer la commande dans la table cart
    $stmt->execute([$totalPanier]);

    // Récupérer l'ID de la commande
    $cart_id = $pdo->lastInsertId();

    // Ajouter les produits du panier dans la table cart_items
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt_product = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt_product->execute([$cart_id, $product_id, $quantity]);
    }

    // Confirmer la transaction
    $pdo->commit();

    // Vider le panier
    unset($_SESSION['cart']);

    echo "Commande confirmée avec succès. Votre numéro de commande est $cart_id.";

} catch (Exception $e) {
    // Annuler la transaction en cas d'erreur
    $pdo->rollBack();
    echo "Erreur lors de la commande: " . $e->getMessage();
}
?>
