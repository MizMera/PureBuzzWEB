<?php
session_start();
include_once 'config.php'; // Inclure la connexion à la base de données

// Récupérer la connexion PDO via la classe Config
$pdo = Config::getConnexion(); 

// Vérifier si le panier est vide
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialiser un panier vide si non défini
}

// Fonction pour afficher les produits du panier
function displayCart($pdo) {
    $cart = $_SESSION['cart'];
    if (empty($cart)) {
        echo "<p id='empty-cart-message' style='color: #f6b92b; font-weight: bold;'>Votre panier est vide.</p>";
        return;
    }

    $totalPanier = 0; // Variable pour calculer le total du panier
    echo "<table id='cart-table'>";
    echo "<thead>
            <tr>
                <th>Produit</th>
                <th>Prix</th>
                <th>Quantité</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
          </thead>";
    echo "<tbody>";

    foreach ($cart as $product_id => $quantity) {
        // Récupérer les informations du produit
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if ($product) {
            $product_total = $product['price'] * $quantity;
            $totalPanier += $product_total;

            echo "<tr data-product-id='{$product_id}'>";
            echo "<td>
                    <img src='{$product['image']}' alt='{$product['name']}' class='product-image' height='50px' width='50px'> 
                    <br>{$product['name']}
                  </td>";
            echo "<td>{$product['price']} TND</td>";
            echo "<td>
                    <button class='quantity-btn' onclick='changeQuantity({$product_id}, -1)'>-</button>
                    <input type='number' min='1' value='{$quantity}' class='quantity-input' onchange='updateQuantity({$product_id}, this.value)'>
                    <button class='quantity-btn' onclick='changeQuantity({$product_id}, 1)'>+</button>
                  </td>";
            echo "<td>{$product_total} TND</td>";
            echo "<td>
                    <button class='remove-btn' onclick='removeProduct({$product_id})'>✕</button>
                  </td>";
            echo "</tr>";
        }
    }

    echo "</tbody>";
    echo "</table>";

    echo "<div id='cart-summary'>
            <p>Subtotal: <span id='subtotal'>{$totalPanier} TND</span></p>
            <p>Shipping: <span id='shipping-cost'>4.500 TND</span></p>
            <p>Total: <strong id='grand-total'>" . ($totalPanier + 4.500) . " TND</strong></p>
            <a class='checkout-btn' href='confirm_order.php'>
                <button id='checkout-btn'>CONFIRM THE ORDER</button>
            </a>
          </div>";
}

// Gestion des modifications du panier via AJAX ou formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $product_id = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        switch ($action) {
            case 'add':
                if (!isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] = 0;
                }
                $_SESSION['cart'][$product_id] += $quantity;
                break;

            case 'remove':
                unset($_SESSION['cart'][$product_id]);
                break;

            case 'update':
                $_SESSION['cart'][$product_id] = max(1, $quantity);
                break;
        }

        // Rediriger pour éviter le rechargement multiple
        header("Location: view_cart.php");
        exit;
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
    <link rel="stylesheet" href="all.css">
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <a class="navbar-brand" href="buzzpage.html">
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

    <!-- Main Cart Section -->
    <div class="main-cart">
        <div class="cart-container">
            <?php displayCart($pdo); ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">PureBuzz &copy; 2024</span>
            <span class="text-muted d-block text-center text-sm-right d-sm-inline-block">Honey, Beekeeping & Bee Essentials</span>
        </div>
    </footer>
</body>
</html>
