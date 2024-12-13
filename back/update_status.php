<?php
session_start();
include_once 'config.php';

$conn = Config::getConnexion();

if (isset($_POST['cart_id']) && isset($_POST['status'])) {
    $cartId = $_POST['cart_id'];
    $status = $_POST['status'];

    $sql = "UPDATE cart SET status = :status WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':status' => $status,
        ':id' => $cartId
    ]);

    // Rediriger vers la page principale
    header('Location: back1.php#carts');
    exit;
} else {
    echo "Données manquantes pour mettre à jour le statut.";
}
?>
