<?php
session_start();
include_once 'C:\xampp\htdocs\project1modif\view\back\config.php';

// Database connection and data retrieval
$conn = Config::getConnexion();
if (!$conn) {
    die("Database connection failed.");
}

$filterStatus = $_GET['status'] ?? '';
$sqlCarts = "SELECT id, total, status FROM cart";
if ($filterStatus) {
    $sqlCarts .= " WHERE status = :status";
    $stmtCarts = $conn->prepare($sqlCarts);
    $stmtCarts->execute([':status' => $filterStatus]);
} else {
    $stmtCarts = $conn->prepare($sqlCarts);
    $stmtCarts->execute();
}
$carts = $stmtCarts->fetchAll(PDO::FETCH_ASSOC);

// Updating the status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_id'], $_POST['new_status'])) {
    $cartId = $_POST['cart_id'];
    $newStatus = $_POST['new_status'];

    $updateSql = "UPDATE cart SET status = :new_status WHERE id = :cart_id";
    $updateStmt = $conn->prepare($updateSql);

    try {
        $updateStmt->execute([':new_status' => $newStatus, ':cart_id' => $cartId]);
        header("Location: cartm.php?status=" . $filterStatus);
        exit;
    } catch (PDOException $e) {
        $error = "Error updating status: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Management</title>
    <link rel="stylesheet" href="catm.css?v=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="all.css">
</head>
<body>
<nav>
    <ul>
        <li><a href="back1modif.php" onclick="showSection('dashboard')">Dashboard</a></li>
        <li><a href="cartm.php">Cart Management</a></li>
    </ul>
</nav>
<br>
<h2>Cart Management</h2>

<form method="GET" action="" class="form-filter">
    <label for="filter"></label>
    <select name="status" id="filter" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="Pending" <?= $filterStatus == 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="In Progress" <?= $filterStatus == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
        <option value="Ready for Delivery" <?= $filterStatus == 'Ready for Delivery' ? 'selected' : '' ?>>Ready for Delivery</option>
        <option value="Delivered" <?= $filterStatus == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
    </select>
</form>

<table>
    <thead>
        <tr>
            <th>Cart ID</th>
            <th>Total (TND)</th>
            <th>Status</th>
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
                    <!-- Status change -->
                    <form method="POST" action="" class="form-change-status">
                        <input type="hidden" name="cart_id" value="<?= $cart['id']; ?>">
                        <select name="new_status" onchange="this.form.submit()">
                            <option value="Pending" <?= $cart['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="In Progress" <?= $cart['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="Ready for Delivery" <?= $cart['status'] == 'Ready for Delivery' ? 'selected' : '' ?>>Ready for Delivery</option>
                            <option value="Delivered" <?= $cart['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                    </form>

                    <!-- Additional actions -->
                    <a href="details.php?id=<?= $cart['id']; ?>#carts" >
                        <button>View Details</button>
                    </a>
                    <a href="delete_cart.php?id=<?= $cart['id']; ?>" onclick="return confirm('Are you sure you want to delete this cart?');">
                        <button>Delete</button>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No carts found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Displaying errors -->
<?php if (isset($error)): ?>
    <p class="error"><?= $error; ?></p>
<?php endif; ?>
</body>
</html>
