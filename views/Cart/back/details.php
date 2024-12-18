<?php
session_start();
include_once __DIR__ . '/../../../config/database.php';

// Connexion à la base de données via PDO
$conn = Database::getConnexion();
// Vérifier si la connexion est bien définie
if (!$conn) {
    die("La connexion à la base de données a échoué.");
}

// Vérifier si un ID de panier a été passé en paramètre
if (!isset($_GET['id'])) {
    die("ID de panier non spécifié.");
}

$cartId = $_GET['id'];

// Récupérer les détails du panier en utilisant cartId
$sql = "SELECT p.name, p.price, ci.quantity
        FROM cartitem ci
        JOIN products p ON ci.productId = p.id
        WHERE ci.cartId = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$cartId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérifier si des produits ont été trouvés pour ce panier
if (empty($cartItems)) {
    echo "<p>Aucun produit trouvé pour ce panier.</p>";
    exit; // Si aucun produit, arrêter le script
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Details</title>
    <link rel="stylesheet" href="bck.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f6b92b;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .close-btn {
            font-size: 32px;
            color: red;
            cursor: pointer;
            float: right;
           
        }
  
    </style>
</head>
<body>

    <div class="container">
        <span class="close-btn" onclick="window.location.href='cartm.php';">&times;</span>

        <h2>Cart Details for Cart ID: <?php echo $cartId; ?></h2>
        
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalCartPrice = 0;
                foreach ($cartItems as $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $totalCartPrice += $subtotal;
                    echo "<tr>
                            <td>{$item['name']}</td>
                            <td>{$item['price']} TND</td>
                            <td>{$item['quantity']}</td>
                            <td>{$subtotal} TND</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>

        <p><strong>Total Cart Price: </strong><span><?php echo number_format($totalCartPrice, 2); ?> TND</span></p>
        
    
    </div>

</body>
</html>
