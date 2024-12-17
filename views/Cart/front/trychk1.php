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
            'unitprice' => $product['price'],
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
$cartCount = 0;

if (!empty($_SESSION['cart'])) {
    // Count total items in the cart
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $cartCount += $quantity; // Increment by quantity of each product
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <link rel="stylesheet" href="copy.css?v=1.0">
    <link rel="stylesheet" href="product-style.css?v=1.0">
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
<br>
    <!-- Header Section -->
    <div class="header">
       
        <div class="welcome-message">
            <h1 style="color:#f6b92b;margin-left:10px" class="welcome-text">Order Confirmation</h1>
            <h3 class="welcome-sub-text">Almost there! Please take a moment to complete your details and we’ll handle the rest</h3>
        </div>
    </div>
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
                <p style="   font-family: 'Verdana', sans-serif;
                            font-weight: bold;
                            text-decoration: underline;
                            font-size: 22px;
                             color: #444444;
                            margin: 15px 0;">Payment Information</p>
                <label for="card-number">Card Number *</label>
                <input type="text" id="card-number" name="card-number" placeholder="Credit Card Number" required>
                <span id="cardNumberError" class="error-message"></span>

                <label for="expiration-date">Expiration Date (MM/YY) *</label>
                <input type="text" id="expiration-date" name="expiration-date" placeholder="MM/YY" required>
                <span id="expirationDateError" class="error-message"></span>

                <label for="cvv">CVV *</label>
                <input type="text" id="cvv" name="cvv" placeholder="CVV" required>
                <span id="cvvError" class="error-message"></span>
                <label class="special-payment">
                <input type="radio" name="payment" value="credit" checked> Credit Card
                </label>
                <label class="special-payment">
                <input type="radio" name="payment" value="paypal"> PayPal
                </label>
                    
                <br>

                <button type="submit" id="place-order" class="place-order-btn">PLACE ORDER</button>
      

            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2>ORDER SUMMARY</h2>
            <div class="order-items">
                <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (TND)</th>
                    <th>Subtotal (TND)</th>
                </tr>
                    <?php foreach ($cartItems as $item) : ?>
                    <tr>
                        <td><?php echo "<p><strong>" . $item['name'] . "</strong></p>";?></td>
                        <td><?php echo  $item['quantity'] . "</p>";?></td>
                        <td><?php echo  number_format($item['unitprice'], 2);?></td>
                        <td><?php echo number_format($item['subtotal'], 3); ?></td>
                    <tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo number_format($totalPanier, 3); ?> TND</strong></td>
                </tr>
                <tr>
                    <td colspan="3"></strong>Shipping</strong></td>
                    <td>4.500 TND</td>
                </tr>
                <tr>
                    <td colspan="3"></strong>Total with Shipping</strong></td>
                    <td><strong><?php echo number_format($totalPanier + 4.500, 3); ?> TND</strong></td>
                </tr>
                </table>
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
   document.getElementById('place-order').addEventListener('click', function(event) {
    event.preventDefault();  // Empêche l'envoi immédiat du formulaire

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

    // Fonction pour réinitialiser les erreurs
    function resetErrors() {
        fields.forEach(function(item) {
            document.getElementById(item.errorSpan).innerText = '';  // Réinitialise le message d'erreur
            item.field.style.borderColor = '';  // Réinitialise la couleur de bordure
            document.getElementById(item.errorSpan).style.color = '';  // Réinitialise la couleur du texte d'erreur
        });
    }

    // Réinitialiser les erreurs avant la validation
    resetErrors();

    // Vérifier les champs à chaque modification
    fields.forEach(function(item) {
        item.field.addEventListener('input', function() {
            let message = '';
            let valid = true;

            // Vérifier si le champ est vide
            if (item.field.value.trim() === '') {
                message = 'Fill this field please';
                valid = false;
            } else {
                // Validation spécifique pour certains champs
                if (item.field === document.getElementById('first-name') || item.field === document.getElementById('last-name')) {
                    if (!/^[a-zA-Z]+$/.test(item.field.value.trim())) {
                        message = `${item.label} should only contain letters.`;
                        valid = false;
                    }
                }

                if (item.field === document.getElementById('phone') && !/^\d{8}$/.test(item.field.value)) {
                    message = 'Phone number must be 8 digits.';
                    valid = false;
                }

                if (item.field === document.getElementById('email') && !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(item.field.value)) {
                    message = 'Please enter a valid email address.';
                    valid = false;
                }

                if (item.field === document.getElementById('card-number') && !/^\d{16}$/.test(item.field.value)) {
                    message = 'Card number must be 16 digits.';
                    valid = false;
                }

                if (item.field === document.getElementById('expiration-date') && !/^(0[1-9]|1[0-2])\/\d{2}$/.test(item.field.value)) {
                    message = 'Expiration date must be in MM/YY format.';
                    valid = false;
                }

                if (item.field === document.getElementById('cvv') && !/^\d{3}$/.test(item.field.value)) {
                    message = 'CVV must be 3 digits.';
                    valid = false;
                }
            }

            // Mettre à jour l'UI selon la validité
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

    // Validation finale avant la soumission du formulaire
    fields.forEach(function(item) {
        if (!item.field.value.trim()) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Fill this field please';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
            errorMessage += `Please fill in the ${item.label} field.\n`;
        }

        // Vérification de la validation spécifique
        if (item.field === document.getElementById('first-name') || item.field === document.getElementById('last-name')) {
            if (!/^[a-zA-Z]+$/.test(item.field.value.trim())) {
                isValid = false;
                document.getElementById(item.errorSpan).innerText = `${item.label} should only contain letters.`;
                document.getElementById(item.errorSpan).style.color = 'red';
                item.field.style.borderColor = 'red';
            }
        }

        if (item.field === document.getElementById('phone') && !/^\d{8}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Phone number must be 8 digits.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === document.getElementById('email') && !/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Please enter a valid email address.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === document.getElementById('card-number') && !/^\d{16}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Card number must be 16 digits.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === document.getElementById('expiration-date') && !/^(0[1-9]|1[0-2])\/\d{2}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'Expiration date must be in MM/YY format.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }

        if (item.field === document.getElementById('cvv') && !/^\d{3}$/.test(item.field.value)) {
            isValid = false;
            document.getElementById(item.errorSpan).innerText = 'CVV must be 3 digits.';
            document.getElementById(item.errorSpan).style.color = 'red';
            item.field.style.borderColor = 'red';
        }
    });


        // Final validation and form submission
        isValid = fields.every(function(item) {
            return item.field.value.trim() !== '' && !document.getElementById(item.errorSpan).innerText.includes('Fill this field please');
        });

        if (isValid) {
            document.getElementById('billing-form').submit();  // Submit the form if all fields are valid
        } else {
            alert('Please fill in all fields correctly before submitting.');
        }
    });
</script>
</body>
</html>



</body>
</html>