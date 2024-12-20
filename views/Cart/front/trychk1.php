<?php
session_start();
include_once __DIR__ . '/../../../config/database.php';
// Connexion à la base de données via PDO

$pdo = Database::getConnexion();
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

    

    try {
        // Commencer la transaction
        $pdo->beginTransaction();

        // Insérer la commande dans la table 'orders'
        $stmt = $pdo->prepare("INSERT INTO orders (first_name, last_name, country, address, city, governorate, phone, email, cart_id) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([ 
    $first_name, $last_name, $country, $address, $city, $governorate, $phone, $email, $cart_id
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
$apiKey = "59cf148e62b51cb6d6b204eb";
$apiUrl = "https://v6.exchangerate-api.com/v6/$apiKey/latest/TND";
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);
if (isset($data['conversion_rates']['USD'])) {
    // Obtenez le taux de change TND à USD
    $exchangeRate = $data['conversion_rates']['USD'];
} else {
    // Si une erreur survient, définir un taux de change par défaut
    $exchangeRate = 0.32; // Taux par défaut
}
$totalWithShippingUSD = ($totalPanier + 4.500) * $exchangeRate;

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
    <script src="https://www.paypal.com/sdk/js?client-id=Adiy4WsKh_9BqAqjhZ0slvYwlFNR7hYhqSvVmflMIBK99-qEi_xelPkmyDisixwXZtghJt2u-vsRxKyj"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>

        .cart-button {
            position: relative;
            padding: 15px; /* Contour entre l'écriture et le cadre */
            width: 440px;
            height: 60px;
            border: 0; /* On peut écrire 0 ou bien none */
            border-radius: 10px;
            background-color:grey;
            outline: none;
            cursor: pointer;
            color: #000;
            transition: 0.3s ease-in-out;
            overflow: hidden;
            font-weight: 700;
            font-size: 16px;
            margin-left: 2px;
        }
        /* Icône du chariot */
        .fa-cart-shopping {
            position: absolute;
            z-index: 2;
            top: 50%;
            left: -10%;
            font-size: 2em;
            transform: translate(-50%, -50%);
            transition: all 1s ease-in-out;
            opacity: 0;
        }

        /* Icône de la boîte */
        .fa-box {
            position: absolute;
            z-index: 3;
            top: -20%;
            left: 52%;
            font-size: 1.2em;
            transform: translate(-50%, -50%);
            color: gold;
            transition: all 1s ease-in-out; /* Ajuster la durée pour correspondre au chariot */
            opacity: 0;
        }

        /* Ajout du texte "Done" et de l'icône de coche */
        .done-message {
            position: absolute;
            opacity: 0;
            font-size: 1.2em;
            color: black; /* Couleur noire pour "Done" */
            top: 50%; /* Centrer verticalement */
            left: 50%; /* Centrer horizontalement */
            transform: translate(-50%, -50%); /* Ajuster pour centrer */
            transition: opacity 0.5s ease-in-out;
            z-index: 4;
        }

        /* État actif */
        .cart-button.active .fa-cart-shopping {
            left: 50%;
            opacity: 1; /* Icône de chariot visible */
        }

        /* La boîte apparaît et descend */
        .cart-button.box-added .fa-box {
            opacity: 1;
            top: 37%; /* La boîte descend dans le chariot */
            transition: all 1s ease-in-out; /* Correspondre à la durée du mouvement du chariot */
        }

        /* Le chariot se déplace vers la droite */
        .cart-button.cart-moving .fa-cart-shopping,
        .cart-button.cart-moving .fa-box {
            left: 120%; /* Déplacer le chariot vers la droite */
        }

        /* Afficher le message "Done" */
        .cart-button.show-done .done-message {
            opacity: 1;
        }

        /* Cacher le texte "Add to cart" */
        .cart-button.active .add-to-cart {
            opacity: 0;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="logo">
            <img src="PureBuzzLogo.png" alt="PureBuzz Logo"> <!-- Replace with the actual logo path -->
        </div>
        <ul class="menu">
            <li><a href="../../../Public/Product-pages/" class="nav-link">About</a></li>
            <li><a href="../../../Public/Product-pages/" class="nav-link">Benefits</a></li>
            <li><a href="../../../Public/Product-pages/" class="nav-link">Support</a></li>
            <li><a href="../../../Public/Product-pages/" class="nav-link">Products</a></li>
            <li><a href="../../../Public/Product-pages/" class="nav-link">Contact</a></li>
            <li><a href="../../../Public/Product-pages/" class="nav-link">My profile</a></li>
        </ul>
        <div class="auth-buttons">

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
                <br>
                <button type="submit" id="place-order" class="cart-button">
                <span class="add-to-cart">PLACE ORDER</span>
                    <i class="fa fa-cart-shopping"></i>
                    <i class="fa fa-box"></i>
                    <div class="done-message">
                        <i class="fa fa-check-circle"></i> Done
                    </div>
                </button>
      

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
                    <td colspan="3" ></strong>Total with Shipping</strong></td>
                    <td><strong><?php echo number_format($totalPanier + 4.500, 3); ?> TND</strong></td>
                </tr>
                </table>
            </div>
            <p style="   font-family: 'Verdana', sans-serif;
                            font-weight: bold;
                            text-decoration: underline;
                            font-size: 22px;
                             color: #444444;
                            margin: 15px 0;">Payment</p>


            <div id="paypal-button-container"></div>

                    <script>
                        paypal.Buttons({
                            createOrder: function(data, actions) {
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: <?php echo number_format($totalWithShippingUSD); ?> // Montant total + frais de 4.500 TND
                                        }
                                    }]
                                });
                            },
                            onApprove: function(data, actions) {
                                return actions.order.capture().then(function(details) {
                                    alert('Transaction réussie, ' + details.payer.name.given_name);
                                });
                            }
                        }).render('#paypal-button-container');
                    </script>
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
    const button = document.getElementById('place-order'); // Define the button element
    const form = document.getElementById('billing-form');
    const fields = [
        { field: document.getElementById('first-name'), errorSpan: 'firstNameError', label: 'First Name', regex: /^[a-zA-Z]+$/, message: 'should only contain letters.' },
        { field: document.getElementById('last-name'), errorSpan: 'lastNameError', label: 'Last Name', regex: /^[a-zA-Z]+$/, message: 'should only contain letters.' },
        { field: document.getElementById('country'), errorSpan: 'countryError', label: 'Country', regex: /.+/, message: 'is required.' },
        { field: document.getElementById('address'), errorSpan: 'addressError', label: 'Address', regex: /.+/, message: 'is required.' },
        { field: document.getElementById('city'), errorSpan: 'cityError', label: 'City', regex: /.+/, message: 'is required.' },
        { field: document.getElementById('governorate'), errorSpan: 'governorateError', label: 'Governorate', regex: /.+/, message: 'is required.' },
        { field: document.getElementById('phone'), errorSpan: 'phoneError', label: 'Phone', regex: /^\d{8}$/, message: 'must be 8 digits.' },
        { field: document.getElementById('email'), errorSpan: 'emailError', label: 'Email', regex: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, message: 'must be a valid email.', optional: true },
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

    });


        // Final validation and form submission
        isValid = fields.every(function(item) {
            return item.field.value.trim() !== '' && !document.getElementById(item.errorSpan).innerText.includes('Fill this field please');
        });

        if (isValid) {
        // Start button animation
        button.classList.add('active');

        // Add the box icon
        setTimeout(() => {
            button.classList.add('box-added');
        }, 1000);

        // Move the cart with the box
        setTimeout(() => {
            button.classList.add('cart-moving');
        }, 2000);

        // Show "Done" and submit the form
        setTimeout(() => {
            button.classList.add('show-done');
            setTimeout(() => {
                form.submit(); // Submit the form after animation
            }, 2000); // Delay to allow the "Done" message to show
        }, 3000);
    }
         else {
            alert('Please fill in all fields correctly before submitting.');
        }
    });
</script>
</body>
</html>



</body>
</html>
