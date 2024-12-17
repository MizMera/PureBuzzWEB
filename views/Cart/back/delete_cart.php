<?php
session_start();
include_once 'C:\xampp\htdocs\project1modif\view\back\config.php';

// Check if cart ID is provided
if (isset($_GET['id'])) {
    $cartId = $_GET['id'];

    try {
        $conn = Config::getConnexion();

        // Delete related cart items
        $deleteItemsSql = "DELETE FROM cartitem WHERE cartId = :cartId";
        $stmtItems = $conn->prepare($deleteItemsSql);
        $stmtItems->execute([':cartId' => $cartId]);

        // Now delete the cart
        $deleteCartSql = "DELETE FROM cart WHERE id = :cartId";
        $stmtCart = $conn->prepare($deleteCartSql);
        $stmtCart->execute([':cartId' => $cartId]);

        // Redirect back to the cart management page
        header("Location: cartm.php");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression du panier : " . $e->getMessage();
    }
}
?>
