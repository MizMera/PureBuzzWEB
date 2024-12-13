<?php
session_start();
include_once 'config.php'; // Include database configuration
$pdo = Config::getConnexion();

// Fetch the order details from session or database (example: cart and user data)
$cart_id = $_SESSION['cart_id']; // Example cart ID from session
$user_id = $_SESSION['user_id']; // Example user ID from session

// Fetch the order details from the database
$stmt = $pdo->prepare("SELECT * FROM cart WHERE id = ?");
$stmt->execute([$cart_id]);
$order = $stmt->fetch();

// Fetch cart items for the order
$stmt_items = $pdo->prepare("SELECT p.name, ci.quantity, p.price FROM cartitem ci
                             JOIN products p ON ci.productid = p.id WHERE ci.cartid = ?");
$stmt_items->execute([$cart_id]);
$cartItems = $stmt_items->fetchAll();

// Fetch the user data for shipping details
$stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt_user->execute([$user_id]);
$user = $stmt_user->fetch();

// Example: Calculate total with shipping
$shipping_cost = 4.500; // Shipping cost
$total = $order['total'] + $shipping_cost;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="try.css">
</head>
<body>
    <!-- Header Section with Logo, Welcome Message, and Progress Indicator -->
    <div class="header">
        <a class="navbar-brand" href="products.php">
            <img src="PureBuzzLogo.png" alt="PureBuzz logo" />
        </a>
        <div class="welcome-message">
            <h1 class="welcome-text">Thank You for Your PureBuzz Order</h1>
            <h3 class="welcome-sub-text">
                 Almost there! Please take a moment to complete your details and we’ll handle the rest.
            </h3>
            
            <!-- Progress Indicator directly under the welcome message -->
            <div class="progress-indicator">
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
    <div class="order-details">
        <h1>Your Order Details</h1>
        <?php foreach ($cartItems as $item): ?>
            <p>Product: <?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?> - Price: <?php echo number_format($item['price'], 3); ?> TND</p>
        <?php endforeach; ?>
        <p>Total: <?php echo number_format($total, 3); ?> TND</p>
        
        <h2>Shipping Address</h2>
        <p>Name: <?php echo htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']); ?></p>
        <p>Address: <?php echo htmlspecialchars($user['address']); ?></p>
        <p>City: <?php echo htmlspecialchars($user['city']); ?></p>
        <p>Country: <?php echo htmlspecialchars($user['country']); ?></p>
        
        <h2>Payment Method</h2>
        <p>Credit Card (**** **** **** 1234)</p>
        
        <h2>Delivery Estimate</h2>
        <p>Your order will be delivered between <?php echo date('F j, Y', strtotime('tomorrow')); ?> and <?php echo date('F j, Y', strtotime('+2 days')); ?>.</p>
        
        <h2>Order Tracking</h2>
        <p>Tracking Number: 123456789</p>
    </div>
</body>
</html>
