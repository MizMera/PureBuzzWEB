<?php
include_once 'C:\Users\omarh\OneDrive\Bureau\xaampppp\htdocs\Projet/config/database.php';

include_once 'C:\Users\omarh\OneDrive\Bureau\xaampppp\htdocs\Projet\Models\Claim.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = htmlspecialchars($_POST['user_name']);
    $product = htmlspecialchars($_POST['product']);
    $claim_details = htmlspecialchars($_POST['claim_details']);
    $claim_date = $_POST['claim_date'];

    if (empty($user_name) || empty($product) || empty($claim_details)) {
        $errorMessage = "Please fill in all required fields.";
        include 'C:\Users\omarh\OneDrive\Bureau\xaampppp\htdocs\Projet\Views\claim_form.php';
        exit();
    }

    $claim = new Claim($pdo);

    $result = $claim->create($user_name, $product, $claim_details, $claim_date);
    if ($result) {
        header("Location: claim_form.php");
        exit();
    } else {
        $errorMessage = "There was an issue submitting your claim.";
        include 'views/claim_form.php';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claims Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="container-scroller">
        <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <div class="me-3">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-bs-toggle="minimize">
                        <span class="icon-menu"></span>
                    </button>
                </div>
                <div>
                    <a class="navbar-brand brand-logo" href="index.html">
                        <img src="logo.png" style="height: 80px;" alt="Company Logo">
                    </a>
                </div>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-top">
                <ul class="navbar-nav">
                    <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                        <h1 class="welcome-text">Submit a New Claim</h1>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="d-flex" style="margin-top: 100px;">
        <nav class="sidebar">
            <ul class="nav">
                <li class="nav-item"><a class="nav-link" href="dashboard.html">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="Orders.html">Orders</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Submit Claim</a></li>
                <li class="nav-item"><a class="nav-link" href="login.html">Logout</a></li>
            </ul>
        </nav>

        <div class="container main-content">
            <section class="claim-form-section">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>

                <form class="claim-form" method="POST" action="claim_form.php">
                    <div class="form-group">
                        <label for="user-name" class="form-label">User Name</label>
                        <input type="text" id="user-name" name="user_name" class="form-control" placeholder="Enter your name" required autocomplete="name">
                    </div>
                    <div class="form-group">
                        <label for="product" class="form-label">Product</label>
                        <input type="text" id="product" name="product" class="form-control" placeholder="Enter the product name" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="claim-details" class="form-label">Claim Details</label>
                        <textarea id="claim-details" name="claim_details" class="form-control" rows="4" placeholder="Describe your claim" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="claim-date" class="form-label">Date</label>
                        <input type="date" id="claim-date" name="claim_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="responded">Responded</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="form-actions d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Submit Claim</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container-fluid">
            <p class="text-muted">&copy; 2024 PureBuzz. All Rights Reserved.</p>
        </div>
    </footer>

</body>

</html>