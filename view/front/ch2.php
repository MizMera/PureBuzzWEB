<?php
session_start();
include_once 'config.php';

try {
    $pdo = Config::getConnexion();

    // Create cart if it doesn't exist
    if (!isset($_SESSION['cartid'])) {
        $stmt_cart = $pdo->prepare("INSERT INTO cart (total) VALUES (0)");
        $stmt_cart->execute();
        $_SESSION['cartid'] = $pdo->lastInsertId();
    }
    $cart_id = $_SESSION['cartid'];

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        die("Le panier est vide. Ajoutez des produits avant de passer une commande.");
    }

    $totalPanier = 0;
    $cartItems = []; // Initialize cart items array
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt_product = $pdo->prepare("SELECT price, name FROM products WHERE id = ?");
        $stmt_product->execute([$product_id]);
        $product = $stmt_product->fetch();

        if ($product) {
            $subtotal = $product['price'] * $quantity;
            $totalPanier += $subtotal;

            $cartItems[] = [
                'name' => htmlspecialchars($product['name']),
                'quantity' => (int)$quantity,
                'price' => number_format($product['price'], 2),
                'subtotal' => number_format($subtotal, 2)
            ];

            $stmt_insert_cartitem = $pdo->prepare(
                "INSERT INTO cartitem (cartid, productid, quantity) VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)"
            );
            $stmt_insert_cartitem->execute([$cart_id, $product_id, $quantity]);
        }
    }

    // Update cart total
    $stmt_update_cart = $pdo->prepare("UPDATE cart SET total = ? WHERE id = ?");
    $stmt_update_cart->execute([$totalPanier, $cart_id]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $inputs = [
            'first_name' => FILTER_SANITIZE_STRING,
            'last_name' => FILTER_SANITIZE_STRING,
            'country' => FILTER_SANITIZE_STRING,
            'address' => FILTER_SANITIZE_STRING,
            'city' => FILTER_SANITIZE_STRING,
            'governorate' => FILTER_SANITIZE_STRING,
            'phone' => FILTER_SANITIZE_STRING,
            'email' => FILTER_VALIDATE_EMAIL,
            'card_number' => FILTER_SANITIZE_NUMBER_INT,
            'expiration_date' => FILTER_SANITIZE_STRING,
            'cvv' => FILTER_SANITIZE_NUMBER_INT,
        ];

        $data = filter_input_array(INPUT_POST, $inputs);

        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare(
                "INSERT INTO orders (first_name, last_name, country, address, city, governorate, phone, email, card_number, expiration_date, cvv, cart_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $data['first_name'], $data['last_name'], $data['country'], $data['address'], 
                $data['city'], $data['governorate'], $data['phone'], $data['email'], 
                $data['card_number'], $data['expiration_date'], $data['cvv'], $cart_id
            ]);

            $order_id = $pdo->lastInsertId();
            $pdo->commit();

            // Clear session cart
            unset($_SESSION['cart'], $_SESSION['cartid']);

            header("Location: order-completed.php?order_id=$order_id");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("Erreur lors de la commande: " . $e->getMessage());
        }
    }
} catch (Exception $e) {
    die("Erreur de connexion: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .error-message {
            color: red;
            font-size: 0.9em;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Checkout Page</h1>
    <form id="billing-form" method="POST" action="checkout.php">
        <div class="form-group">
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first_name" required>
            <div id="firstNameError" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last_name" required>
            <div id="lastNameError" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" id="city" name="city" required>
        </div>

        <div class="form-group">
            <label for="governorate">Governorate:</label>
            <input type="text" id="governorate" name="governorate" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <div id="phoneError" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <div id="emailError" class="error-message"></div>
        </div>

        <h2>Payment Information</h2>
        <div class="form-group">
            <label for="card-number">Card Number:</label>
            <input type="text" id="card-number" name="card_number" required>
            <div id="cardNumberError" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="expiration-date">Expiration Date (MM/YY):</label>
            <input type="text" id="expiration-date" name="expiration_date" required>
            <div id="expirationDateError" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required>
            <div id="cvvError" class="error-message"></div>
        </div>

        <button type="submit">Submit Order</button>
    </form>

    <script>
        // JavaScript for validation (same as in the previous message)
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('billing-form');
            const errorMessages = {
                firstName: 'First name should only contain letters.',
                lastName: 'Last name should only contain letters.',
                phone: 'Phone number must be 10 digits.',
                email: 'Invalid email format.',
                cardNumber: 'Card number must be 16 digits.',
                expirationDate: 'Expiration date must be in MM/YY format.',
                cvv: 'CVV must be 3 digits.'
            };

            const validateField = (field, regex, errorId, errorMessage) => {
                const value = field.value.trim();
                const errorElement = document.getElementById(errorId);

                if (!value || !regex.test(value)) {
                    errorElement.textContent = errorMessage;
                    field.style.borderColor = 'red';
                    return false;
                }

                errorElement.textContent = '';
                field.style.borderColor = 'green';
                return true;
            };

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                const validations = [
                    validateField(document.getElementById('first-name'), /^[a-zA-Z]+$/, 'firstNameError', errorMessages.firstName),
                    validateField(document.getElementById('last-name'), /^[a-zA-Z]+$/, 'lastNameError', errorMessages.lastName),
                    validateField(document.getElementById('phone'), /^\d{10}$/, 'phoneError', errorMessages.phone),
                    validateField(document.getElementById('email'), /^[^\s@]+@[^\s@]+\.[^\s@]+$/, 'emailError', errorMessages.email),
                    validateField(document.getElementById('card-number'), /^\d{16}$/, 'cardNumberError', errorMessages.cardNumber),
                    validateField(document.getElementById('expiration-date'), /^(0[1-9]|1[0-2])\/\d{2}$/, 'expirationDateError', errorMessages.expirationDate),
                    validateField(document.getElementById('cvv'), /^\d{3}$/, 'cvvError', errorMessages.cvv)
                ];

                if (validations.every(Boolean)) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>
