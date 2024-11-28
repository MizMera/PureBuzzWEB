<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PureBuzz Honey Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgba(255, 235, 204, 0.7);
            color: #3e2723; /* Dark honey tones for text */
        }
        header {
            position: fixed;
            width: 100%;
            background-color: rgba(62, 39, 35, 0.8); /* Transparent header */
            padding: 0;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        header img {
            height: 70px;
        }
        header nav a {
            color: #fbc02d; /* Golden honey tone */
            margin: 0 15px;
            text-decoration: none;
            font-weight: bold;
        }
        header button {
            background-color: #fbc02d;
            color: #3e2723;
            border: none;
            padding: 10px 20px;
            margin-right: 2%;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        .background-section {
            height: 50vh; /* Full-screen height */
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 70%, rgba(255, 235, 204, 0.7) 100%), 
            url('ABC.png');
            background-size: cover;
            background-position: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        }
        .background-section h1 {
            font-size: 4rem;
            margin: 0;
            padding: 0;
            border: none;
            text-align: center;
            background-position: top center;
            background-size: cover;
            overflow: hidden;
        }
        section {
            padding: 40px 20px;
            background-color: rgba(255, 235, 204, 0.7); /* Transparent honey-like background */
            border-radius: 10px;
            margin: 20px auto;
            max-width: 1200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .events {
            padding: 40px 20px;
        }
        .event-card {
            background-color: rgba(255, 236, 179, 0.9); /* Light transparent honey shade for cards */
            border: 1px solid rgba(215, 204, 200, 0.8); /* Slightly transparent border */
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .event-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-bottom: 3px solid rgba(251, 192, 45, 0.9); /* Transparent honey border under image */
            border-radius: 10px 10px 0 0;
        }
        .event-card h3 {
            color: #3e2723;
            margin-top: 15px;
        }
        footer {
            background-color: rgba(62, 39, 35, 0.8); /* Transparent footer */
            color: #fff8e1;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<header>
    <img src="logo.png" alt="PureBuzz Logo">
    <nav>
        <a href="#events">Events</a>
        <a href="#contact">Contact</a>
    </nav>
    <button onclick="location.href='joinus.php'" class="boton">Get Tickets</button>
</header>

<div class="background-section">
    <h1>Welcome to PureBuzz Honey Events</h1>
</div>

<section id="events" class="events">
    <h2>Our Events</h2>

    <?php
    // Fetch events from the database
    try {
        // Database connection details
        $dsn = 'mysql:host=localhost;dbname=purebuzz;charset=utf8mb4';
        $username = 'root'; // Replace with your database username
        $password = ''; // Replace with your database password

        // Create a PDO instance
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch events
        $stmt = $pdo->query("SELECT name, location, description, img FROM events");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate event cards dynamically
        if (!empty($events)) {
            foreach ($events as $event) {
                echo "<div class='event-card'>";
                echo "<img src='" . htmlspecialchars($event['img']) . "' alt='" . htmlspecialchars($event['name']) . "'>";
                echo "<h3>" . htmlspecialchars($event['name']) . "</h3>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($event['location']) . "</p>";
                echo "<p>" . htmlspecialchars($event['description']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No events available at the moment.</p>";
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo "<p>Error fetching events: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</section>

<footer>
    <p>&copy; 2024 PureBuzz Honey Events. All Rights Reserved.</p>
</footer>

</body>
</html>