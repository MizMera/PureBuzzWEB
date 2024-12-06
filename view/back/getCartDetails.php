<?php
include_once 'C:\xampp\htdocs\project1modif\view\back\config.php';

// Obtenir la connexion à la base de données
$conn = Config::getConnexion();

// Vérifier si l'ID du panier est passé en paramètre
if (isset($_GET['id'])) {
    $cartId = $_GET['id'];

    // Récupérer les produits du panier
    $sql = "SELECT p.name, p.price, ci.quantity
            FROM cartitem ci
            JOIN products p ON ci.productId = p.id
            WHERE ci.cartId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cartId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cartItems); // Renvoie les données sous forme de JSON
} else {
    echo json_encode([]); // Aucun panier trouvé
}
?>
