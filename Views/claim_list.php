<?php
// admin_claims.php

include_once 'C:\Users\omarh\OneDrive\Bureau\xaampppp\htdocs\Projet/config/database.php';
include_once 'C:\Users\omarh\OneDrive\Bureau\xaampppp\htdocs\Projet/Models/Claim.php';

// Instantiate database and claim model
$claim = new Claim($pdo);

// Retrieve all claims
$claims = $claim->getAllClaims();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Claims Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="container-scroller">
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="dashboard.php">
                    <img src="logo.png" style="height: 80px;" alt="Company Logo">
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Claims Management</h1>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h2 class="text-center mb-4">All Submitted Claims</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Product</th>
                    <th>Details</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($claims)) : ?>
                    <?php foreach ($claims as $claim) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($claim['id']); ?></td>
                            <td><?php echo htmlspecialchars($claim['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($claim['product']); ?></td>
                            <td><?php echo htmlspecialchars($claim['claim_details']); ?></td>
                            <td><?php echo htmlspecialchars($claim['claim_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">No claims found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container">
            <p class="text-muted">&copy; 2024 PureBuzz. All Rights Reserved.</p>
        </div>
    </footer>
</body>

</html>