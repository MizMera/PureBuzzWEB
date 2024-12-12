<?php
// Get coordinates from the URL
$coordinates = isset($_GET['cor']) ? $_GET['cor'] : '0,0'; // Default to '0,0' if no coordinates are provided
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map - PureBuzz</title>
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

        /* Map Container */
        .map-container {
            width: 100%;
            height: 500px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }

       
    </style>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
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


    <!-- Map Section -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Apiary Location</h4>
                    <p>Coordinates: <strong><?php echo htmlspecialchars($coordinates); ?></strong></p>
                    <div id="map" class="map-container"></div>
                </div>
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

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        const coordinates = '<?php echo $coordinates; ?>'.split(',').map(coord => parseFloat(coord.trim()));

        // Initialize the map
        const map = L.map('map').setView([coordinates[0], coordinates[1]], 13); // Lat, Lng

        // Add OSM Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add Marker
        L.marker([coordinates[0], coordinates[1]]).addTo(map)
            .bindPopup('<b>Apiary Location</b><br>Lat: ' + coordinates[0] + ', Lng: ' + coordinates[1])
            .openPopup();
    </script>
</body>
</html>
