<?php
session_start();
include_once 'config.php'; // Inclure la configuration de la base de données

// Obtenir la connexion PDO via la classe Config
$pdo = Config::getConnexion();

// Lorsque vous ajoutez un article au panier
if (!isset($_SESSION['cartid'])) {
    $stmt_cart = $pdo->prepare("INSERT INTO cart (total) VALUES (0)");
    $stmt_cart->execute();
    $_SESSION['cartid'] = $pdo->lastInsertId();
}
$cart_id = $_SESSION['cartid'];

// Vérifier si le panier est vide
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Le panier est vide. Ajoutez des produits avant de passer une commande.");
}

// Calculer le total du panier et ajouter les articles au panier
$totalPanier = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Récupérer le prix du produit
    $stmt_product = $pdo->prepare("SELECT price, name FROM products WHERE id = ?");
    $stmt_product->execute([$product_id]);
    $product = $stmt_product->fetch();

    if ($product) {
        $subtotal = $product['price'] * $quantity;
        $totalPanier += $subtotal;

        // Ajouter l'article au tableau des éléments du panier
        $cartItems[] = [
            'name' => $product['name'],
            'quantity' => $quantity,
            'price' => $product['price'],
            'subtotal' => $subtotal
        ];

// Vérifier si l'article existe déjà dans le panier
$stmt_check_cartitem = $pdo->prepare("SELECT quantity FROM cartitem WHERE cartid = ? AND productid = ?");
$stmt_check_cartitem->execute([$cart_id, $product_id]);
$existingItem = $stmt_check_cartitem->fetch();

if ($existingItem) {
    // Remplacer la quantité actuelle dans la base de données
    $stmt_update_cartitem = $pdo->prepare("UPDATE cartitem SET quantity = ? WHERE cartid = ? AND productid = ?");
    $stmt_update_cartitem->execute([$quantity, $cart_id, $product_id]);
} else {
    // Insérer un nouvel article si ce n'est pas déjà dans le panier
    $stmt_insert_cartitem = $pdo->prepare("INSERT INTO cartitem (cartid, productid, quantity) VALUES (?, ?, ?)");
    $stmt_insert_cartitem->execute([$cart_id, $product_id, $quantity]);
}


    }
}

// Mettre à jour le total du panier dans la table 'cart'
$stmt_update_cart = $pdo->prepare("UPDATE cart SET total = ? WHERE id = ?");
$stmt_update_cart->execute([$totalPanier, $cart_id]);

// Vérifier si le formulaire a été soumis pour la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les informations envoyées par le formulaire
    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $country = $_POST['country'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $governorate = $_POST['governorate'];
    $phone = $_POST['phone'];
    $email = $_POST['email'] ?? null;
    $card_number = $_POST['card-number'];
    $expiration_date = $_POST['expiration-date'];
    $cvv = $_POST['cvv'];
    

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Insérer la commande dans la table 'orders'
        $stmt = $pdo->prepare("INSERT INTO orders (first_name, last_name, country, address, city, governorate, phone, email, card_number, expiration_date, cvv, cart_id) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([ 
    $first_name, $last_name, $country, $address, $city, $governorate, $phone, $email, 
    $card_number, $expiration_date, $cvv, $cart_id
]);


        // Récupérer l'ID de la commande insérée
        $order_id = $pdo->lastInsertId();

        // Finaliser la transaction
        $pdo->commit();

        // Vider le panier après la commande
        unset($_SESSION['cart']);
        unset($_SESSION['cartid']);

        // Rediriger vers une page de confirmation
        header("Location: final1.php?order_id=$order_id");
        exit;
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $pdo->rollBack();
        die("Erreur lors de la commande: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="all_copy.css?v=1.0">
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

    <!-- Order Summary -->
    <div class="checkout-container">
        <div class="billing-details">
            <h2>BILLING DETAILS</h2>
            <form id="billing-form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <label for="first-name">First Name *</label>
                <input type="text" id="first-name" name="first-name" placeholder="First Name" required>
                <span id="firstNameError" class="error-message"></span>

                <label for="last-name">Last Name *</label>
                <input type="text" id="last-name" name="last-name" placeholder="Last Name" required>
                <span id="lastNameError" class="error-message"></span>

                <label for="country">Country/Region *</label>
                <input type="text" id="country" name="country" placeholder="Country/Region" required>
                <span id="countryError" class="error-message"></span>

                <label for="address">Address *</label>
                <input type="text" id="address" name="address" placeholder="Street address and street name" required>
                <span id="addressError" class="error-message"></span>

                <label for="city">City *</label>
                <input type="text" id="city" name="city" placeholder="City" required>
                <span id="cityError" class="error-message"></span>

                <label for="governorate">Governorate *</label>
                <input type="text" id="governorate" name="governorate" placeholder="Governorate" required>
                <span id="governorateError" class="error-message"></span>

                <label for="phone">Phone *</label>
                <input type="tel" id="phone" name="phone" placeholder="Phone" required>
                <span id="phoneError" class="error-message"></span>

                <label for="email">Email (optional)</label>
                <input type="email" id="email" name="email" placeholder="Email">
                <span id="emailError" class="error-message"></span>
                <p>Payment Information</p>
                <label for="card-number">Card Number *</label>
                <input type="text" id="card-number" name="card-number" placeholder="Credit Card Number" required>
                <span id="cardNumberError" class="error-message"></span>

                <label for="expiration-date">Expiration Date (MM/YY) *</label>
                <input type="text" id="expiration-date" name="expiration-date" placeholder="MM/YY" required>
                <span id="expirationDateError" class="error-message"></span>

                <label for="cvv">CVV *</label>
                <input type="text" id="cvv" name="cvv" placeholder="CVV" required>
                <span id="cvvError" class="error-message"></span>

           
                <button type="submit" id="place-order" class="place-order-btn">PLACE ORDER</button>
      

            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2>ORDER SUMMARY</h2>
            <div class="order-items">
                <?php
                foreach ($cartItems as $item) {
                    echo "<div class='order-item'>";
                    echo "<p><strong>" . $item['name'] . "</strong></p>";
                    echo "<p>Quantity: " . $item['quantity'] . "</p>";
                    echo "<p>Subtotal: " . number_format($item['subtotal'], 2) . " TND</p>";
                    echo "</div>";
                }
                ?>
            </div>
            <tr>
                <td colspan="3">Shipping</td>
                <td>4.500 TND</td>
            </tr>
            <br>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong><?php echo number_format($totalPanier + 4.500, 3); ?> TND</strong></td>
            </tr>
        </div>
    </div>
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">PureBuzz &copy; 2024</span>
            <span class="text-muted d-block text-center text-sm-right d-sm-inline-block">Honey, Beekeeping & Bee Essentials</span>
        </div>
    </footer>
   

    <script>
        document.getElementById('place-order').addEventListener('click', function(event) {
            event.preventDefault();

            let isValid = true;
            let errorMessage = '';

            const fields = [
                { field: document.getElementById('first-name'), errorSpan: 'firstNameError', label: 'First Name', regex: /^[a-zA-Z]+$/, message: 'should only contain letters.' },
                { field: document.getElementById('last-name'), errorSpan: 'lastNameError', label: 'Last Name', regex: /^[a-zA-Z]+$/, message: 'should only contain letters.' },
                { field: document.getElementById('country'), errorSpan: 'countryError', label: 'Country', regex: /.+/, message: 'is required.' },
                { field: document.getElementById('address'), errorSpan: 'addressError', label: 'Address', regex: /.+/, message: 'is required.' },
                { field: document.getElementById('city'), errorSpan: 'cityError', label: 'City', regex: /.+/, message: 'is required.' },
                { field: document.getElementById('governorate'), errorSpan: 'governorateError', label: 'Governorate', regex: /.+/, message: 'is required.' },
                { field: document.getElementById('phone'), errorSpan: 'phoneError', label: 'Phone', regex: /^\d{8}$/, message: 'must be 8 digits.' },
                { field: document.getElementById('email'), errorSpan: 'emailError', label: 'Email', regex: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, message: 'must be a valid email.', optional: true },
                { field: document.getElementById('card-number'), errorSpan: 'cardNumberError', label: 'Card Number', regex: /^\d{16}$/, message: 'must be 16 digits.' },
                { field: document.getElementById('expiration-date'), errorSpan: 'expirationDateError', label: 'Expiration Date', regex: /^(0[1-9]|1[0-2])\/\d{2}$/, message: 'must be in MM/YY format.' },
                { field: document.getElementById('cvv'), errorSpan: 'cvvError', label: 'CVV', regex: /^\d{3}$/, message: 'must be 3 digits.' }
            ];

            fields.forEach(item => {
                const value = item.field.value.trim();
                const errorSpan = document.getElementById(item.errorSpan);

                errorSpan.innerText = ''; // Reset error
                item.field.style.borderColor = '';

                if (value === '' && !item.optional) {
                    isValid = false;
                    errorSpan.innerText = `${item.label} ${item.message}`;
                    errorSpan.style.color = 'red';
                    item.field.style.borderColor = 'red';
                } else if (value !== '' && !item.regex.test(value)) {
                    isValid = false;
                    errorSpan.innerText = `${item.label} ${item.message}`;
                    errorSpan.style.color = 'red';
                    item.field.style.borderColor = 'red';
                }
            });

            if (isValid) {
                document.getElementById('billing-form').submit();
            }
        });
    </script>
</body>
</html>