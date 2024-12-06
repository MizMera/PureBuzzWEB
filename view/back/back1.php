<?php
session_start();
include_once 'C:\xampp\htdocs\project1modif\view\back\config.php'; // Vérifie que ce fichier existe et est bien configuré

// Obtenir la connexion à la base de données
$conn = Config::getConnexion();

// Vérifier si la connexion est bien définie
if (!$conn) {
    die("La connexion à la base de données a échoué.");
}

// Récupérer le nombre total de paniers
$sqlTotalCarts = "SELECT COUNT(id) AS total_carts FROM cart";
$stmtTotalCarts = $conn->prepare($sqlTotalCarts);
$stmtTotalCarts->execute();
$rowTotalCarts = $stmtTotalCarts->fetch(PDO::FETCH_ASSOC);
$totalCarts = $rowTotalCarts['total_carts'];

// Récupérer la somme des totaux des paniers
$sqlTotalSales = "SELECT SUM(total) AS total_sales FROM cart";
$stmtTotalSales = $conn->prepare($sqlTotalSales);
$stmtTotalSales->execute();
$rowTotalSales = $stmtTotalSales->fetch(PDO::FETCH_ASSOC);
$totalSales = $rowTotalSales['total_sales'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backoffice - Administration</title>
    <link rel="stylesheet" href="bck.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
</head>
<body>

    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="#dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="#carts" onclick="showSection('carts')">Cart Management</a></li>
        </ul>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Dashboard Section -->
        <div id="dashboard" class="section">
            <h2>Dashboard</h2>
            <div class="statistics">
                <div class="stat-box">
                    <h3>Total Carts</h3>
                    <p><?php echo $totalCarts; ?></p> <!-- Affichage du nombre total de paniers -->
                </div>
                <div class="stat-box">
                    <h3>Total Users</h3>
                    <p>85</p> <!-- Nombre d'utilisateurs (peut être dynamique selon ta base) -->
                </div>
                <div class="stat-box">
                    <h3>Total Sales</h3>
                    <p><?php echo number_format($totalSales, 2); ?>TND</p> <!-- Affichage de la somme des paniers -->
                </div>
            </div>
        </div>

        <!-- Cart Management Section -->
        <div id="carts" class="section">
            <h2>Cart Management</h2>
            <p>List of user carts</p>

            <table>
                <thead>
                    <tr>
                        <th>Cart ID</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer les détails des paniers
                    $sqlCarts = "SELECT id, total FROM cart"; // Récupérer les colonnes id et total
                    $stmtCarts = $conn->prepare($sqlCarts);
                    $stmtCarts->execute();
                    $carts = $stmtCarts->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($carts as $cart) {
                        echo "<tr>
                                <td>{$cart['id']}</td>
                                <td>€" . number_format($cart['total'], 2) . "</td>
                                <td class='actions'>
                                    <a href='details.php?id={$cart['id']}' style='text-decoration: none;'>
                                        <button>View Details</button>
                                    </a>
                                    <button onclick='deleteCart({$cart['id']})'>Delete</button>
                                </td>
                              </tr>";
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Cart Details -->
    <div id="cartDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>Cart Details</h2>
            <div id="cartDetails">
                <!-- Cart details will be dynamically added here -->
            </div>
        </div>
    </div>

    <script src="back.js"></script>

</body>
</html>