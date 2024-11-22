<?php
session_start();

// Si l'action est 'update', on met à jour la quantité
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['new_quantity'];

    // On cherche le produit dans le panier et on met à jour la quantité
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productId) {
            $item['quantity'] = $newQuantity; // Mettre à jour la quantité
            break;
        }
    }

    // Recalculer le sous-total et le total après la mise à jour
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $product) {
        $subtotal += $product['price'] * $product['quantity'];
    }

    $shipping = 4.500; // Coût de la livraison
    $total = $subtotal + $shipping;

    // Retourner les nouveaux montants sous forme de JSON
    echo json_encode([
        'subtotal' => number_format($subtotal, 3),
        'shipping' => number_format($shipping, 3),
        'total' => number_format($total, 3)
    ]);
}
?>
