<?php 
session_start();
include_once 'config.php'; // Include database config

// Get the PDO connection via Config class
$pdo = Config::getConnexion();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Ensure the form has been submitted before accessing POST data

    // Get billing details from the form
    $first_name = isset($_POST['first-name']) ? $_POST['first-name'] : '';
    $last_name = isset($_POST['last-name']) ? $_POST['last-name'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $governorate = isset($_POST['governorate']) ? $_POST['governorate'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
    $expiration_date = isset($_POST['expiration_date']) ? $_POST['expiration_date'] : '';
    $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';

    // Start the transaction
    $pdo->beginTransaction();

    try {
        // Add the order into the cart table
        $stmt = $pdo->prepare("INSERT INTO cart (total) VALUES (?)");
        $totalPanier = 0;

        // Calculate the cart total
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Get the product price
            $stmt_product = $pdo->prepare("SELECT price, name FROM products WHERE id = ?");
            $stmt_product->execute([$product_id]);
            $product = $stmt_product->fetch();

            if ($product) {
                $subtotal = $product['price'] * $quantity;
                $totalPanier += $subtotal;

                // Store information for display
                $cartItems[] = [
                    'name' => $product['name'],
                    'quantity' => $quantity,
                    'price' => $product['price'],
                    'subtotal' => $subtotal
                ];
            }
        }

        // Insert the order into the cart table
        $stmt->execute([$totalPanier]);

        // Get the order ID
        $cart_id = $pdo->lastInsertId();

        // Add cart items to the cart_items table
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stmt_product = $pdo->prepare("INSERT INTO cartitem (cartid, productid, quantity) VALUES (?, ?, ?)");
            $stmt_product->execute([$cart_id, $product_id, $quantity]);
        }

        // Insert order details into the orders table
        $stmt_order = $pdo->prepare("INSERT INTO orders (cart_id, first_name, last_name, country, address, city, governorate, phone, email, card_number, expiration_date, cvv) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt_order->execute([
            $cart_id, $first_name, $last_name, $country, $address, $city, 
            $governorate, $phone, $email, 
            $card_number, $expiration_date, $cvv
        ]);

        // Commit the transaction
        $pdo->commit();

        // Empty the cart
        unset($_SESSION['cart']);
    } catch (Exception $e) {
        // Rollback in case of an error
        $pdo->rollBack();
        die("<h2>Erreur lors de la commande : " . $e->getMessage() . "</h2>");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="all_copy.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header Section -->
    <div class="header">
        <a class="navbar-brand" href="products.php">
            <img src="PureBuzzLogo.png" alt="PureBuzz logo" />
        </a>
        <div class="welcome-message">
            <h1 class="welcome-text">Order Confirmation</h1>
            <h3 class="welcome-sub-text">Almost there! Please take a moment to complete your details and we’ll handle the rest</h3>
        </div>
    </div>
    <!-- Progress Indicator -->
    <div class="progress-indicator">
        <span class="step"><span class="step-number">1</span> CART</span>
        <span class="separator"> &gt; </span>
        <span class="step active"><span class="step-number">2</span> ORDER DETAILS</span>
        <span class="separator"> &gt; </span>
        <span class="step"><span class="step-number">3</span> ORDER COMPLETED</span>
    </div>
    <div class="checkout-container">
        <div class="billing-details">
            <h2>BILLING DETAILS</h2>
            <form id="billing-form" method="POST" action="">
                <!-- Form Inputs with name attributes for proper submission -->
                <label for="first-name">First Name *</label>
                <input type="text" name="first-name" id="first-name" value="<?php echo htmlspecialchars($first_name ?? '', ENT_QUOTES); ?>" required>
                <label for="last-name">Last Name *</label>
                <input type="text" name="last-name" id="last-name" value="<?php echo htmlspecialchars($last_name ?? '', ENT_QUOTES); ?>" required>
                <label for="country">Country/Region *</label>
                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($country ?? '', ENT_QUOTES); ?>" required>
                <label for="address">Address *</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($address ?? '', ENT_QUOTES); ?>" required>
                <label for="city">City *</label>
                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($city ?? '', ENT_QUOTES); ?>" required>
                <label for="governorate">Governorate *</label>
                <input type="text" name="governorate" id="governorate" value="<?php echo htmlspecialchars($governorate ?? '', ENT_QUOTES); ?>" required>
                <label for="phone">Phone *</label>
                <input type="tel" name="phone" id="phone" value="<?php echo htmlspecialchars($phone ?? '', ENT_QUOTES); ?>" required>
                <label for="email">Email (optional)</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>">
                <label for="card_number">Card Number</label>
                <input type="text" name="card_number" id="card_number" value="<?php echo htmlspecialchars($card_number ?? '', ENT_QUOTES); ?>" required>
                <label for="expiration_date">Expiration Date (MM/YY)</label>
                <input type="text" name="expiration_date" id="expiration_date" value="<?php echo htmlspecialchars($expiration_date ?? '', ENT_QUOTES); ?>" required>
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" id="cvv" value="<?php echo htmlspecialchars($cvv ?? '', ENT_QUOTES); ?>" required>
                <button type="submit" class="submit-button">Submit Order</button>
            </form>
        </div>
    </div>
</body>
</html>
