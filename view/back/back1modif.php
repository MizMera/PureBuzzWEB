<?php
session_start();
include_once 'C:\xampp\htdocs\project1modif\view\back\config.php';

// Obtenir la connexion à la base de données
$conn = Config::getConnexion();
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

// Récupérer les données pour le tableau
$filterStatus = $_GET['status'] ?? '';
$sqlCarts = "SELECT id, total, status FROM cart";
if ($filterStatus) {
    $sqlCarts .= " WHERE status = :status";
    $stmtCarts->execute([':status' => $filterStatus]);
} else {
    $stmtCarts = $conn->prepare($sqlCarts);
    $stmtCarts->execute();
}
$carts = $stmtCarts->fetchAll(PDO::FETCH_ASSOC);

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['new_status'])) {
    $cartId = $_POST['cart_id'];
    $newStatus = $_POST['new_status'];

    $updateSql = "UPDATE cart SET status = :new_status WHERE id = :cart_id";
    $updateStmt = $conn->prepare($updateSql);

    try {
        $updateStmt->execute([':new_status' => $newStatus, ':cart_id' => $cartId]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=" . $filterStatus . "#carts");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour du statut : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Management</title>
    <link rel="stylesheet" href="bck.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <ul>
            <li><a href="#dashboard" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="cartm.php">Cart Management</a></li>
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
                    <p><?= $totalCarts; ?></p>
                </div>
                <div class="stat-box">
                    <h3>Total Users</h3>
                    <p>85</p>
                </div>
                <div class="stat-box">
                    <h3>Total Sales</h3>
                    <p><?= number_format($totalSales, 2); ?> TND</p>
                </div>
            </div>
        </div>

        <!-- Cart Management Section -->
        <div id="carts" class="section">
            <h2>Cart Management</h2>

            <!-- Filtrage par statut -->
            <form method="GET" action="" class="form-filter">
    <label for="filter"></label>
    <select name="status" id="filter" onchange="this.form.submit()">
        <option value="">Tous</option>
        <option value="En attente" <?= $filterStatus == 'En attente' ? 'selected' : '' ?>>En attente</option>
        <option value="En cours" <?= $filterStatus == 'En cours' ? 'selected' : '' ?>>En cours</option>
        <option value="Prête pour la livraison" <?= $filterStatus == 'Prête pour la livraison' ? 'selected' : '' ?>>Prête pour la livraison</option>
        <option value="Livrée" <?= $filterStatus == 'Livrée' ? 'selected' : '' ?>>Livrée</option>
    </select>
</form>


            <!-- Affichage des paniers -->
            <table>
                <thead>
                    <tr>
                        <th>ID Panier</th>
                        <th>Total (TND)</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($carts)): ?>
                        <?php foreach ($carts as $cart): ?>
                            <tr>
                                <td><?= $cart['id']; ?></td>
                                <td><?= number_format($cart['total'], 2); ?> TND</td>
                                <td><?= htmlspecialchars($cart['status']); ?></td>
                                <td>
                                    <!-- Changement de statut -->
                                    <form method="POST" action="" class="form-change-status">
    <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
    <select name="new_status" onchange="this.form.submit()">
        <option value="En attente" <?= $cart['status'] == 'En attente' ? 'selected' : '' ?>>En attente</option>
        <option value="En cours" <?= $cart['status'] == 'En cours' ? 'selected' : '' ?>>En cours</option>
        <option value="Prête pour la livraison" <?= $cart['status'] == 'Prête pour la livraison' ? 'selected' : '' ?>>Prête pour la livraison</option>
        <option value="Livrée" <?= $cart['status'] == 'Livrée' ? 'selected' : '' ?>>Livrée</option>
    </select>
</form>


                                    <!-- Actions supplémentaires -->
                                    <a href="details.php?id=<?= $cart['id']; ?>#carts">
                                        <button>View Details</button>
                                    </a>
                                    <button onclick="deleteCart(<?= $cart['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Aucun panier trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Affichage des erreurs -->
            <?php if (isset($error)): ?>
                <p class="error"><?= $error; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script src="back.js"></script>
</body>
</html>
