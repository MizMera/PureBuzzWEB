<?php
session_start();
include_once 'config.php';

// Connexion à la base de données via PDO
$pdo = Config::getConnexion();

// Fonction pour afficher les produits dans le panier
function displayCart($pdo) {
    // Vérifier si le panier (session) existe
    if (empty($_SESSION['cart'])) {
        echo "<p id='empty-cart-message' style='color: #f6b92b; font-weight: bold;'>Votre panier est vide.</p>";
        return;
    }

    $cart = $_SESSION['cart']; // Récupérer les éléments du panier
    $totalPanier = 0; // Total général du panier

    echo "<table id='cart-table'>";
    echo "<thead>
            <tr>
                <th>Produit</th>
                <th>Prix Unitaire</th>
                <th>Quantité</th>
                <th>Sous-total</th>
                <th>Action</th>
            </tr>
          </thead>";
    echo "<tbody>";
    foreach ($cart as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
    
        if ($product) {
            $subtotal = $product['price'] * $quantity;
            $totalPanier += $subtotal;
    
            echo "<tr data-product-id='{$product['id']}'>";
            echo "<td>{$product['name']}</td>";
            echo "<td>{$product['price']} TND</td>";
            echo "<td>
            <button class='quantity-btn' onclick='changeQuantity({$product['id']}, -1)'>-</button>
            <input type='number' min='1' value='{$quantity}' class='quantity-input' data-product-id='{$product['id']}' onchange='updateQuantity({$product['id']}, this.value)'>
            <button class='quantity-btn' onclick='changeQuantity({$product['id']}, 1)'>+</button>
          </td>";
    
            echo "<td>{$subtotal} TND</td>";
            echo "<td>
                    <button class='remove-btn' onclick='removeProduct({$product['id']})'>✕</button>
                  </td>";
            echo "</tr>";
        }
    }

    echo "</tbody>";
    echo "</table>";
    }

// Calculer le total panier pour l'affichage dans le résumé
$totalPanier = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        if ($product) {
            $totalPanier += $product['price'] * $quantity;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - PureBuzz</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all_copy.css">

</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <a class="navbar-brand" href="products.php">
            <img src="PureBuzzLogo.png" alt="PureBuzz logo" />
        </a>
        <div class="welcome-message">
            <h1 class="welcome-text">Review Your PureBuzz Cart</h1>
            <h3 class="welcome-sub-text">
                Double-check your favorite honey, bee products, and more before completing your order. 
                Pure and natural, directly from our hive to yours!
            </h3>
        </div>
    </div>

    <!-- Progress Indicator -->
    <div class="progress-indicator">
        <span class="step active">
            <span class="step-number">1</span> CART
        </span>
        <span class="separator"> &gt; </span>
        <span class="step">
            <span class="step-number">2</span> ORDER DETAILS
        </span>
        <span class="separator"> &gt; </span>
        <span class="step">
            <span class="step-number">3</span> ORDER COMPLETED
        </span>
    </div>

    <!-- Cart Page Container -->
    <div class="cart-page-container">
        <!-- Main Cart Section -->
        <div class="main-cart">
            <div class="cart-container">
                <?php displayCart($pdo); ?>
                <div class="continue-shopping-container">
                    <a href="products.php">
                        <button class="continue-shopping">← CONTINUE SHOPPING</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Cart Summary Section -->
        <div id="cart-summary">
            <p>Subtotal: <span id="subtotal">
                <?php echo number_format($totalPanier ?? 0, 3); ?> TND
            </span></p>
            <p>Shipping: <span id="shipping-cost">4.500 TND</span></p>
            <p>Total: <strong id="grand-total">
                <?php echo number_format(($totalPanier ?? 0) + 4.500, 3); ?> TND
            </strong></p>
            <a class="checkout-btn" href="trychk1.php">
                <button id="checkout-btn">CONFIRM THE ORDER</button>
            </a>
            
            <div class="promo-code">
                <p>Promo Code</p>
                <input type="text" placeholder="Promo code">
                <br>
                <button class="apply-promo-btn" onclick="applyPromo()">Apply Promo Code</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">PureBuzz &copy; 2024</span>
            <span class="text-muted d-block text-center text-sm-right d-sm-inline-block">Honey, Beekeeping & Bee Essentials</span>
        </div>
    </footer>
    <script>
    // Function to remove a product from the cart
    function removeProduct(productId) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                location.reload(); // Refresh the page to update the cart
            }
        };
        xhr.send('action=remove&product_id=' + productId);
    }

    // Function to change quantity
    function changeQuantity(productId, delta) {
        const quantityInput = document.querySelector(`input[data-product-id="${productId}"]`);
        let newQuantity = parseInt(quantityInput.value) + delta;
        if (newQuantity < 1) return; // Prevent reducing quantity below 1
        quantityInput.value = newQuantity;

        updateQuantity(productId, newQuantity);
    }

    // Function to update quantity
    function updateQuantity(productId, quantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                location.reload(); // Refresh the page to update the cart
            }
        };
        xhr.send('action=update&product_id=' + productId + '&quantity=' + quantity);
    }
</script>

</body>
</html>
