<?php
// Connexion à la base de données
include_once 'config.php'; 
$pdo = Config::getConnexion();

// Vérifier si l'ID de la commande est présent dans l'URL
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']); // Sécurisation de l'entrée

    // Récupérer les détails de la commande depuis la base de données
    $stmt_order = $pdo->prepare("
        SELECT o.*, c.total 
        FROM orders o 
        JOIN cart c ON o.cart_id = c.id 
        WHERE o.id = ?
    ");
    $stmt_order->execute([$order_id]);
    $order = $stmt_order->fetch();

    if ($order) {
        // Récupérer les articles du panier associés à cette commande
        $stmt_items = $pdo->prepare("
        SELECT p.name, ci.quantity, p.price AS unitprice 
        FROM cartitem ci 
        JOIN products p ON ci.productid = p.id 
        WHERE ci.cartid = ?
    ");
    
        $stmt_items->execute([$order['cart_id']]);
        $cartItems = $stmt_items->fetchAll();

        // Calculer le total au cas où
        $total = $order['total'];
    } else {
        die("<p>La commande spécifiée est introuvable.</p>");
    }
} else {
    die("<p>Erreur : L'ID de la commande est manquant.</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="product-style.css?v=1.0">
    <link rel="stylesheet" href="try1.css?v=1.0">
   
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
      
        </div>
    </nav>
    <br>
    <!-- Header Section -->
    <div>
       
        <div class="welcome-message">
            <h1 class="welcome-text" style="color:#f6b92b;margin-left:10px">Thank You for Your PureBuzz Order</h1>
            <h3 class="welcome-sub-text"style="margin-left:20px;">
                 Almost there! Please take a moment to complete your details and we’ll handle the rest.
            </h3>
            <div class="progress-indicator" style="margin-left:500px;">
                <span class="step">
                    <span class="step-number">1</span> CART
                </span>
                <span class="separator"> &gt; </span>
                <span class="step">
                    <span class="step-number">2</span> ORDER DETAILS
                </span>
                <span class="separator"> &gt; </span>
                <span class="step active">
                    <span class="step-number">3</span> ORDER COMPLETED
                </span>
            </div>
        </div>
    </div>
    
    <!-- Order Details Section -->
    <div style="width:300px; margin-left:550px;margin-bottom:70px" class="order-details">
        <h1>Your Order Details</h1>
        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['cart_id']); ?></p>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address'] . ', ' . $order['city'] . ', ' . $order['country']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
        
        <h2>Products:</h2>
        <ul>
            <?php foreach ($cartItems as $item): ?>
                <li>
                    <?php echo htmlspecialchars($item['name']); ?> × <?php echo intval($item['quantity']); ?> - 
                    <?php echo number_format($item['unitprice'] , 2); ?> TND
                    <br>
                    <span>&bull;</span> Total: <?php echo number_format($item['unitprice'] * $item['quantity'], 2); ?> TND
                </li>
            <?php endforeach; ?>
        </ul>
        
        <h2>Total(+4.500)</h2>
        <p> <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong><?php echo number_format($total + 4.500, 3); ?> TND</strong></td>
        </tr>
    </p>
       
        <h2>Delivery Estimate</h2>
        <p>Your order will be delivered between 
            <?php echo date('d/m/Y', strtotime('+1 day')); ?> and 
            <?php echo date('d/m/Y', strtotime('+3 days')); ?>.</p>
        
        <h2>Order Tracking</h2>
        <p>Tracking Number: <strong>123456789</strong></p>
    </div>
<div class="submit">
    <a href="test1.php?order_id=<?php echo $order_id; ?>">
        <button class="download-btn">Download PDF</button>
    </a>
</div>
     <!-- Footer -->
     <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">made <a href="https://www.purebuzz.com/" target="_blank">by team webnovators</a> from Esprit.</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved.</span>
        </div>
      </footer>

</body>
</html>
