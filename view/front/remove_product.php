<?php
session_start();

$product_id = $_POST['product_id'];

// Remove product from cart
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);

    $cartTotal = array_reduce($_SESSION['cart'], function ($sum, $qty) use ($pdo) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([key($_SESSION['cart'])]);
        $product = $stmt->fetch();
        return $sum + ($product['price'] * $qty);
    }, 0);

    echo json_encode(['success' => true, 'cartTotal' => $cartTotal]);
} else {
    echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
}
?>
