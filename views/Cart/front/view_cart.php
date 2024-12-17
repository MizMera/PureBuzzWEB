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
                <th>Product</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
          </thead>";
    echo "<tbody>";
    foreach ($cart as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT id, name, price,image_url FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
    
        if ($product) {
            $subtotal = $product['price'] * $quantity;
            $totalPanier += $subtotal;
    
            echo "<td><img src='{$product['image_url']}' alt='{$product['name']}' style='width: 50px; height: 50px; margin-right: 10px;'></td>";
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
$cartCount = 0;

if (!empty($_SESSION['cart'])) {
    // Count total items in the cart
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $cartCount += $quantity; // Increment by quantity of each product
    }
}
// Initialiser les variables
$promoApplied = false;
$promoDiscount = 0;

// Vérifier si un code promo a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['promo_code']) && !empty($_POST['promo_code'])) {
    $promoCode = $_POST['promo_code'];

    // Vérifier si le code promo est valide dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM promos WHERE code = ? AND CURDATE() BETWEEN valid_from AND valid_until");
    $stmt->execute([$promoCode]);
    $promo = $stmt->fetch();

    if ($promo) {
        $promoApplied = true;
        // Appliquer la réduction en fonction du type de promo
        if ($promo['discount_type'] == 'percentage') {
            $promoDiscount = ($totalPanier * $promo['discount_value']) / 100;
        } elseif ($promo['discount_type'] == 'flat') {
            $promoDiscount = $promo['discount_value'];
        }
    } else {
        $promoApplied = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - PureBuzz</title>
    <link rel="stylesheet" href="product-style.css?v=1.0">
    <link rel="stylesheet" href="copy.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


</head>
<body>
<nav class="navbar">
        <div class="logo">
            <img src="PureBuzzLogo.png" alt="PureBuzz Logo"> <!-- Replace with the actual logo path -->
        </div>
        <ul class="menu">
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#benefits" class="nav-link">Benefits</a></li>
            <li><a href="#support" class="nav-link">Support</a></li>
            <li><a href="#product-section" class="nav-link">Products</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
        </ul>
        <div class="auth-buttons">
            <a href="#" class="signin">Sign in</a>
            <a href="#" class="register">Register</a>
        </div>
</nav>
        <!-- Cart Icon Section -->
        <div class="cart-icon-container"style="   position: fixed; 
            top: 10px; 
            left: 10px; 
            display: flex;
            align-items: center;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 50%;
            margin-top: 91px;
            margin-left:1434px">
                <i class="fa fa-shopping-basket" aria-hidden="true" style="font-size: 24px;
            margin-right: 10px;"></i>
                <span id="cart-item-count" class="cart-item-count" style="  font-size: 16px;
            font-weight: bold;
            color: #f6b92b; "><?php echo $cartCount; ?></span> <!-- Display cart count -->
        
        </div>

<br>
   
    <!-- Header Section -->
    <div class="header">
        <div class="welcome-message">
            <h1 class="welcome-text"style="color:#f6b92b;margin-left:10px">Review Your PureBuzz Cart</h1>
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
        <p>Subtotal: <span id="subtotal"><?php echo number_format($totalPanier, 3); ?> TND</span></p>
    <p>Shipping: <span id="shipping-cost">4.500 TND</span></p>
    
    <?php
    // Calculate the grand total considering the promo discount (if any)
    if (isset($promoDiscount) && $promoDiscount > 0) {
        // If promo code is applied, subtract the discount
        $grandTotal = ($totalPanier + 4.500) - $promoDiscount;
    } else {
        // If no promo code, just sum up the total and shipping
        $grandTotal = ($totalPanier + 4.500);
    }
    ?>
    
    <p>Total: <strong id="grand-total"><?php echo number_format($grandTotal, 3); ?> TND</strong></p>
            <a class="checkout-btn" href="trychk1.php">
                <button id="checkout-btn">CONFIRM THE ORDER</button>
            </a>
            
            <div class="promo-code">
                    <p>Promo Code</p>
                    <form method="POST">
                        <input type="text" name="promo_code" placeholder="Promo code" value="<?php echo $_POST['promo_code'] ?? ''; ?>">
                        <br>
                        <button class="apply-promo-btn" type="submit">Apply Promo Code</button>
                    </form>
                    <?php if ($promoApplied): ?>
                        <p style="color: green;">Promo code applied! Discount: <?php echo number_format($promoDiscount, 2); ?> TND</p>
                    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                        <p style="color: red;">Invalid promo code or expired!</p>
                    <?php endif; ?>
            </div>
        </div>
    </div>

     <!-- Footer -->
     <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">made <a href="https://www.purebuzz.com/" target="_blank">by team webnovators</a> from Esprit.</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved.</span>
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
