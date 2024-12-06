<?php
session_start();
include_once 'config.php'; // Include database config

// Get the PDO connection via Config class
$pdo = Config::getConnexion();

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

    // Commit the transaction
    $pdo->commit();

    // Empty the cart
    unset($_SESSION['cart']);
} catch (Exception $e) {
    // Rollback in case of an error
    $pdo->rollBack();
    die("<h2>Erreur lors de la commande : " . $e->getMessage() . "</h2>");
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
    <span class="step">
      <span class="step-number">1</span> CART
    </span>
    <span class="separator"> &gt; </span>
    <span class="step active">
      <span class="step-number">2</span> ORDER DETAILS
    </span>
    <span class="separator"> &gt; </span>
    <span class="step">
      <span class="step-number">3</span> ORDER COMPLETED
    </span>
  </div>
    <div class="checkout-container">
        <div class="billing-details">
            <h2>BILLING DETAILS</h2>
            <form id="billing-form" method="POST" action="">
            <label for="first-name">First Name *</label>
      <input type="text" id="first-name" placeholder="First Name" required>
      <span id="firstNameError" class="error-message"></span>

      <label for="last-name">Last Name *</label>
      <input type="text" id="last-name" placeholder="Last Name" required>
      <span id="lastNameError" class="error-message"></span>

      <label for="country">Country/Region *</label>
      <input type="text" id="country" placeholder="Country/Region" required>
      <span id="countryError" class="error-message"></span>

      <label for="address">Address *</label>
      <input type="text" id="address" placeholder="Street address and street name" required>
      <span id="addressError" class="error-message"></span>

      <label for="city">City *</label>
      <input type="text" id="city" placeholder="City" required>
      <span id="cityError" class="error-message"></span>

      <label for="governorate">Governorate *</label>
      <input type="text" id="governorate" placeholder="Governorate" required>
      <span id="governorateError" class="error-message"></span>

      <label for="phone">Phone *</label>
      <input type="tel" id="phone" placeholder="Phone" required>
      <span id="phoneError" class="error-message"></span>

      <label for="email">Email (optional)</label>
      <input type="email" id="email" placeholder="Email">
      <span id="emailError" class="error-message"></span>

<!-- Add other inputs for card number, expiration date, CVV etc. with their respective error spans -->
                <!-- Bouton pour soumettre le formulaire -->
                
            </form>
        </div>
            <!-- Order Summary -->
    <div class="order-summary">
        <h2>Your Order</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (TND)</th>
                <th>Subtotal (TND)</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 3); ?></td>
                    <td><?php echo number_format($item['subtotal'], 3); ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Shipping</td>
                <td>4.500 TND</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong><?php echo number_format($totalPanier + 4.500, 3); ?> TND</strong></td>
            </tr>
        </table>
        
      <section id="shipping-method">
        <p>Shipping Method</p>
        <div class="shipping-options">
          <label><input type="radio" name="shipping" value="standard" checked> Standard Shipping</label>
          <label><input type="radio" name="shipping" value="express"> Express Shipping</label>
        </div>
      </section>

      <section id="payment-info">
        <p>Payment Information</p>
        <input type="text" placeholder="Credit Card Number" required>
        <span id="cardNumberError" class="error-message"></span>
        <br>
        <input type="text" placeholder="Expiration Date (MM/YY)" required>
        <span id="expirationDateError" class="error-message"></span>
        <br>
        <input type="text" placeholder="CVV" required>
        <span id="cvvError" class="error-message"></span>
        <br>
        <label><input type="radio" name="payment" value="credit" checked> Credit Card</label>
        <label><input type="radio" name="payment" value="paypal"> PayPal</label>
      </section>

      <form action="final.php" method="POST">
    <!-- Le bouton pour passer la commande -->
    <button type="button" id="place-order" class="place-order-btn">PLACE ORDER</button>
    
    <!-- Afficher l'ID de la commande -->
    <p>Your Order Number: <strong><?php echo $cart_id; ?></strong></p>
</form>

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
  document.getElementById('place-order').addEventListener('click', function(event) {
    // Prevent the default behavior of the button if validation fails
    event.preventDefault();

    let isValid = true;
    let errorMessage = '';

    // Get all fields and error spans
    const firstName = document.getElementById('first-name');
    const lastName = document.getElementById('last-name');
    const country = document.getElementById('country');
    const address = document.getElementById('address');
    const city = document.getElementById('city');
    const governorate = document.getElementById('governorate');
    const phone = document.getElementById('phone');
    const email = document.getElementById('email');
    const cardNumber = document.querySelector('#payment-info input[placeholder="Credit Card Number"]');
    const expirationDate = document.querySelector('#payment-info input[placeholder="Expiration Date (MM/YY)"]');
    const cvv = document.querySelector('#payment-info input[placeholder="CVV"]');

    // Function to reset all error messages and styles
    function resetErrors() {
        const fields = [
            { field: firstName, errorSpan: 'firstNameError' },
            { field: lastName, errorSpan: 'lastNameError' },
            { field: country, errorSpan: 'countryError' },
            { field: address, errorSpan: 'addressError' },
            { field: city, errorSpan: 'cityError' },
            { field: governorate, errorSpan: 'governorateError' },
            { field: phone, errorSpan: 'phoneError' },
            { field: email, errorSpan: 'emailError' },
            { field: cardNumber, errorSpan: 'cardNumberError' },
            { field: expirationDate, errorSpan: 'expirationDateError' },
            { field: cvv, errorSpan: 'cvvError' }
        ];

        fields.forEach(function(item) {
            document.getElementById(item.errorSpan).innerText = '';  // Reset error message
            item.field.style.borderColor = '';  // Reset border color
            document.getElementById(item.errorSpan).style.color = '';  // Reset error text color
        });
    }

    // Reset previous errors before new validation
    resetErrors();

    // Real-time validation for all fields
    const fields = [
        { field: firstName, errorSpan: 'firstNameError', label: 'First Name' },
        { field: lastName, errorSpan: 'lastNameError', label: 'Last Name' },
        { field: country, errorSpan: 'countryError', label: 'Country' },
        { field: address, errorSpan: 'addressError', label: 'Address' },
        { field: city, errorSpan: 'cityError', label: 'City' },
        { field: governorate, errorSpan: 'governorateError', label: 'Governorate' },
        { field: phone, errorSpan: 'phoneError', label: 'Phone' },
        { field: email, errorSpan: 'emailError', label: 'Email' },
        { field: cardNumber, errorSpan: 'cardNumberError', label: 'Card Number' },
        { field: expirationDate, errorSpan: 'expirationDateError', label: 'Expiration Date' },
        { field: cvv, errorSpan: 'cvvError', label: 'CVV' }
    ];

    // Add real-time validation on input for each field
    fields.forEach(function(item) {
        item.field.addEventListener('input', function() {
            let message = '';
            let valid = true;

            // Check for empty fields
            if (item.field.value.trim() === '') {
                message = 'Fill this field please';
                valid = false;
            } else {
                // Additional specific validation per field
                if (item.field === firstName || item.field === lastName) {
                    if (!/^[a-zA-Z]+$/.test(item.field.value.trim())) {
                        message = `${item.label} should only contain letters.`;
                        valid = false;
                    }
                }

                if (item.field === phone && !/^\d{10}$/.test(item.field.value)) {
                    message = 'Phone number must be 10 digits.';
                    valid = false;
                }

                if (item.field === email && !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(item.field.value)) {
                    message = 'Please enter a valid email address.';
                    valid = false;
                }

                if (item.field === cardNumber && !/^\d{16}$/.test(item.field.value)) {
                    message = 'Card number must be 16 digits.';
                    valid = false;
                }

                if (item.field === expirationDate && !/^(0[1-9]|1[0-2])\/\d{2}$/.test(item.field.value)) {
                    message = 'Expiration date must be in MM/YY format.';
                    valid = false;
                }

                if (item.field === cvv && !/^\d{3}$/.test(item.field.value)) {
                    message = 'CVV must be 3 digits.';
                    valid = false;
                }
            }

            // Update UI based on validity
            if (valid) {
                document.getElementById(item.errorSpan).innerText = 'Valid';
                document.getElementById(item.errorSpan).style.color = 'green';
                item.field.style.borderColor = 'green';
            } else {
                document.getElementById(item.errorSpan).innerText = message;
                document.getElementById(item.errorSpan).style.color = 'red';
                item.field.style.borderColor = 'red';
            }
        });
    });

    // Final validation before form submission
    fields.forEach(function(item) {
        if (!item.field.value.trim()) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Fill this field please';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
            errorMessage += `Please fill in the ${item.label} field.\n`;
        }

        // Check for each field after validation
        if (item.field === firstName || item.field === lastName) {
            if (!/^[a-zA-Z]+$/.test(item.field.value.trim())) {
                isValid = false;
                document.getElementById(item.errorSpan).innerText = `${item.label} should only contain letters.`;
                document.getElementById(item.errorSpan).style.color = 'red';
                item.field.style.borderColor = 'red';
            }
        }

        if (item.field === phone && !/^\d{10}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Phone number must be 10 digits.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === email && !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Please enter a valid email address.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === cardNumber && !/^\d{16}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Card number must be 16 digits.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === expirationDate && !/^(0[1-9]|1[0-2])\/\d{2}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Expiration date must be in MM/YY format.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === cvv && !/^\d{3}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'CVV must be 3 digits.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }
    });

    if (!isValid) {
        alert('There are errors in your form. Please fix them and try again.');
    } else {
        // Proceed with form submission if valid
        // form.submit();
        alert('Order placed successfully!');
        window.location.href = "final.php";  // Remplacez par l'URL de votre page de confirmation
    }
});
</script>


</body>
</html>
