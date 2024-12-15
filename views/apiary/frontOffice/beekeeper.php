<?php

include_once "../../config.php";
include_once "../../Controllers/apiariesC.php";

$current_beekeper_email = "sarrabenothmen@gmail.com";

$apiaryC = new ApiaryC();

$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'ASC'; // Default sorting order

// Pagination settings
$limit = 4; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit;

// Fetch the total count of apiaries for search
$totalApiaries = $apiaryC->countApiariesWithSearch1($current_beekeper_email,$search);
$totalPages = ceil($totalApiaries / $limit);

// Fetch the filtered and sorted list of apiaries
$listApiaries = $apiaryC->fetchFilteredSortedApiaries1($current_beekeper_email,$search, $sort, $limit, $offset);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_apiary'])) {
    $apiaryName = $_POST['apiaryName'];
    $beekeeper = $current_beekeper_email;
    $location = $_POST['location'];
    $coordinates = $_POST['coordinates'];
    $date = $_POST['date'];
    $weather = $_POST['weather'];
    $hiveCount = $_POST['hiveCount'];
    $observation = $_POST['observation'];

    // Create the Apiary object
    $apiary = new Apiary(null, $apiaryName, $beekeeper, $location, $coordinates, $date, $weather, $hiveCount, $observation);

    // Add the Apiary to the database
    $apiaryC->ajouterApiary($apiary);

    // Redirect to the apiaries list page or show a success message
    header("Location: beekeeper.php");
    exit();
}


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
    <section class="hero">
        <h1>Welcome to PureBuzz</h1>
        <p>Your trusted source for premium beekeeping products, natural honey, and health-boosting bee by-products. Discover our wide range of offerings!</p>
        <a href="#product-section" class="cta-button">Shop Now</a>
    </section>

    <!-- About Us Section -->
    <section id="about" class="info-section">
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
    <section id="add-apiary" class="contact-section">
    <h2>Add New Apiary</h2>
    
    <form action="beekeeper.php" method="POST" onsubmit="return validateForm()" class="contact-form" novalidate>
        <label for="apiaryName">Apiary Name</label>
        <input type="text" id="apiaryName" name="apiaryName" placeholder="Enter Apiary Name" required>
        <small style="color:red;" id="apiaryNameError"></small>
        
        <label for="location">Location</label>
        <input type="text" id="location" name="location" placeholder="Enter Location" required>
        <small style="color:red;" id="locationError"></small>
        
        <label for="coordinates">Coordinates</label>
        <input type="text" id="coordinates" name="coordinates" placeholder="Enter Coordinates">
        <small style="color:red;" id="coordinatesError"></small>
        
        <label for="date">Establishment Date</label>
        <input type="date" id="date" name="date" required>
        <small style="color:red;" id="dateError"></small>
        
        <label for="weather">Weather Condition</label>
        <input type="text" id="weather" name="weather" placeholder="Enter Weather Condition">
        <small style="color:red;" id="weatherError"></small>
        
        <label for="hiveCount">Hive Count</label>
        <input type="number" id="hiveCount" name="hiveCount" placeholder="Enter Number of Hives" required>
        <small style="color:red;" id="hiveCountError"></small>
        
        <label for="observation">Observation</label>
        <textarea id="observation" name="observation" placeholder="Enter Observations" rows="4"></textarea>
        <small style="color:red;" id="observationError"></small>
        
        <button type="submit" name="add_apiary">Add Apiary</button>
    </form>
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
        <script>
    function validateForm() {
        let isValid = true;

        document.querySelectorAll('small.text-danger').forEach(error => error.textContent = '');

        const apiaryName = document.getElementById('apiaryName').value.trim();
        const location = document.getElementById('location').value.trim();
        const coordinates = document.getElementById('coordinates').value.trim();
        const date = document.getElementById('date').value.trim();
        const weather = document.getElementById('weather').value.trim();
        const hiveCount = document.getElementById('hiveCount').value.trim();
        const observation = document.getElementById('observation').value.trim();

        // Validation rules
        if (apiaryName === '') {
            document.getElementById('apiaryNameError').textContent = "Apiary Name is required.";
            isValid = false;
        }

        if (location === '') {
            document.getElementById('locationError').textContent = "Location is required.";
            isValid = false;
        }

        if (coordinates === '') {
            document.getElementById('coordinatesError').textContent = "Coordinates are required.";
            isValid = false;
        } else if (!/^-?\d{1,3}\.\d+,\s*-?\d{1,3}\.\d+$/.test(coordinates)) {
            document.getElementById('coordinatesError').textContent = "Coordinates must be in the format: latitude, longitude (e.g., 36.7783, -119.4179).";
            isValid = false;
        }

        if (date === '') {
            document.getElementById('dateError').textContent = "Establishment Date is required.";
            isValid = false;
        }

        if (weather === '') {
            document.getElementById('weatherError').textContent = "Weather condition is required.";
            isValid = false;
        }

        if (hiveCount === '' || isNaN(hiveCount) || hiveCount <= 0) {
            document.getElementById('hiveCountError').textContent = "Hive Count must be a positive number.";
            isValid = false;
        }

        if (observation.length > 255) {
            document.getElementById('observationError').textContent = "Observation cannot exceed 255 characters.";
            isValid = false;
        }

        return isValid; 
    }
</script>
</body>
</html>
