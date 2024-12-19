
<?php

include_once "../../Controllers/apiariesC.php";
include_once "../../Controllers/harvestsC.php";


$host = 'localhost';  // Nom de l'hôte (ou adresse IP)
$dbname = 'purebuzz_db'; // Nom de la base de données
$username = 'root';   // Nom d'utilisateur pour la connexion
$password = '';       // Mot de passe pour la connexion (si applicable)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
    exit;
}

$harvestC = new HarvestC();
$apiaryC = new ApiaryC();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC'; // Default sorting order

// Pagination settings
$limit = 4; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit;

// Fetch the total count of apiaries for search
$totalApiaries = $apiaryC->countApiariesWithSearch($search);
$totalPages = ceil($totalApiaries / $limit);

// Fetch the filtered and sorted list of apiaries
$listApiaries = $apiaryC->fetchFilteredSortedApiaries($search, $sort, $limit, $offset);


$search1 = isset($_GET['search1']) ? $_GET['search1'] : '';
$sort1 = isset($_GET['sort1']) ? $_GET['sort1'] : 'ASC'; // Default sorting order

// Pagination settings
$limit1 = 4; // Items per page
$page1 = isset($_GET['page1']) ? (int)$_GET['page1'] : 1; // Current page
$offset1 = ($page1 - 1) * $limit1;
// Fetch the total count of harvests for search
$totalHarvests = $harvestC->countHarvestsWithSearch($search1);
$totalPages = ceil($totalHarvests / $limit1);

// Fetch the filtered and sorted list of harvests
$listHarvests = $harvestC->fetchFilteredSortedHarvests($search1, $sort1, $limit1, $offset1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - PureBuzz</title>
    <link rel="stylesheet" href="product-style.css">
    <style>
   /* General Reset */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

/* Card */
.card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Table */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.custom-table th, .custom-table td {
    text-align: left;
    padding: 12px 15px;
    border: 1px solid #ddd;
}

.custom-table th {
    background-color: #007bff;
    color: #fff;
    text-transform: uppercase;
    font-size: 14px;
}

.custom-table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.custom-table tbody tr:hover {
    background-color: #e9ecef;
    transition: background-color 0.3s;
}

/* Search and Sort Bar */
.search-sort-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-input, .sort-select, .btn-submit {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    outline: none;
    font-size: 14px;
}

.search-input {
    flex: 1;
    margin-right: 10px;
}

.sort-select {
    width: 200px;
    margin-right: 10px;
}

.btn-submit {
    background-color: #007bff;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn-submit:hover {
    background-color: #0056b3;
}

/* Actions Buttons */
.btn-action {
    text-decoration: none;
    padding: 5px 10px;
    margin-right: 5px;
    border-radius: 5px;
    color: white;
    font-size: 12px;
    text-align: center;
}

.modify {
    background-color: #ffc107;
}

.delete {
    background-color: #dc3545;
}

.add {
    background-color: #28a745;
}

.btn-action:hover {
    opacity: 0.8;
}

    </style>
</head>
<body>

    <!-- Navigation Bar -->
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

    <!-- Hero Section -->


    <!-- About Us Section -->
    <section>
    <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List of Apiaries</h4>
                <!-- Search and Sort -->
                <form method="GET" class="search-sort-bar">
                    <input type="text" name="search" class="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                    <select name="sort" class="sort-select">
                        <option value="ASC" <?php echo $sort == 'ASC' ? 'selected' : ''; ?>>Date Ascending</option>
                        <option value="DESC" <?php echo $sort == 'DESC' ? 'selected' : ''; ?>>Date Descending</option>
                    </select>
                    <button type="submit" class="btn-submit">Search</button>
                </form>
                <div class="table-wrapper">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Apiary Name</th>
                                <th>Beekeeper</th>
                                <th>Location</th>
                                <th>Coordinates</th>
                                <th>Date</th>
                                <th>Weather</th>
                                <th>Hive Count</th>
                                <th>Observation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listApiaries)) {
                                foreach ($listApiaries as $index => $apiary) {
                                    echo "<tr>
                                        <td>" . ($offset + $index + 1) . "</td>
                                        <td>" . htmlspecialchars($apiary['apiaryName']) . "</td>
                                        <td>" . htmlspecialchars($apiary['beekeeper']) . "</td>
                                        <td>" . htmlspecialchars($apiary['location']) . "</td>
                                        <td>" . htmlspecialchars($apiary['coordinates']) . "</td>
                                        <td>" . htmlspecialchars($apiary['date']) . "</td>
                                        <td>" . htmlspecialchars($apiary['weather']) . "</td>
                                        <td>" . htmlspecialchars($apiary['hiveCount']) . "</td>
                                        <td>" . htmlspecialchars($apiary['observation']) . "</td>
                                        <td>
                                            <a href='map.php?cor=" . $apiary['coordinates'] . "' class='signin'>Show in map</a>
                                        </td>

                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center'>No apiaries found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="pagination-link">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="pagination-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>" class="pagination-link">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    </section>
        <!-- About Us Section -->
        <section>
        <div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List of Harvests</h4>
                <!-- Search and Sort -->
                <form method="GET" class="search-sort-bar">
                    <input type="text" name="search1" class="search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search1); ?>">
                    <select name="sort1" class="sort-select">
                        <option value="ASC" <?php echo $sort1 == 'ASC' ? 'selected' : ''; ?>>Date Ascending</option>
                        <option value="DESC" <?php echo $sort1 == 'DESC' ? 'selected' : ''; ?>>Date Descending</option>
                    </select>
                    <button type="submit" class="btn-submit">Search</button>
                </form>
                <div class="table-wrapper">
                <table class="custom-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Quantity</th>
                                <th>Quality</th>
                                <th>Apiary Name</th>
       
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($listHarvests)) {
                                foreach ($listHarvests as $index => $harvest) {
                                    echo "<tr>
                                        <td>" . ($offset + $index + 1) . "</td>
                                        <td>" . htmlspecialchars($harvest['date']) . "</td>
                                        <td>" . htmlspecialchars($harvest['location']) . "</td>
                                        <td>" . htmlspecialchars($harvest['quantity']) . "</td>
                                        <td>" . htmlspecialchars($harvest['quality']) . "</td>
                                        <td>" . htmlspecialchars($harvest['apiaryName']) . "</td>
                                      
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7' class='text-center'>No harvests found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
               <!-- Pagination -->
               <div class="pagination">
                    <?php if ($page1 > 1): ?>
                        <a href="?page1=<?php echo $page1 - 1; ?>&search1=<?php echo urlencode($search1); ?>&sort1=<?php echo $sort1; ?>" class="pagination-link">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page1=<?php echo $i; ?>&search1=<?php echo urlencode($search1); ?>&sort1=<?php echo $sort1; ?>" class="pagination-link <?php echo $i == $page1 ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page1 < $totalPages): ?>
                        <a href="?page1=<?php echo $page1 + 1; ?>&search1=<?php echo urlencode($search1); ?>&sort1=<?php echo $sort1; ?>" class="pagination-link">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
          <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">made <a href="https://www.purebuzz.com/" target="_blank">by team webnovators</a> from Esprit.</span>
          <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved.</span>
        </div>
      </footer>

    <!-- JavaScript for Smooth Scrolling and Scroll Animation -->
    <script>
        // Smooth Scroll for Navigation Links
        document.querySelectorAll('.nav-link').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Scroll Animation for Product Catalog
        document.addEventListener("DOMContentLoaded", function() {
            const productCatalog = document.querySelector(".product-catalog");

            function checkScroll() {
                const sectionPosition = productCatalog.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;

                if (sectionPosition < screenPosition) {
                    productCatalog.classList.add("show");
                    window.removeEventListener("scroll", checkScroll); // Remove event listener after showing
                }
            }

            window.addEventListener("scroll", checkScroll);
            checkScroll(); // Initial check in case the section is already in view
        });
    </script>
</body>
</html>
