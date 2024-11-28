<?php
session_start(); // Start the session to use session variables

// Database connection details
$dsn = 'mysql:host=localhost;dbname=purebuzz;charset=utf8mb4';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Initialize variables for feedback messages
$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Prepare and execute the insert statement
    $stmt = $pdo->prepare("INSERT INTO tickets (username, email, phone, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
    
    if ($stmt->execute([$name, $email, $phone])) {
        // Set success message in the session
        $_SESSION['message'] = "Thank you, $name! Your ticket has been booked.";
        $_SESSION['message_type'] = "success";
    } else {
        // Set error message in the session
        $_SESSION['message'] = "There was an error booking your ticket. Please try again.";
        $_SESSION['message_type'] = "error";
    }

    // Redirect to the same page to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check for messages in the session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['message_type'];
    unset($_SESSION['message']); // Clear the message after displaying it
    unset($_SESSION['message_type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Us - Honey Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff8e1;
            color: #3e2723;
        }
        header {
            background-color: rgba(62, 39, 35, 0.8);
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        header img {
            height: 50px;
        }
        header h1 {
            color: #fff8e1;
            margin: 0;
            font-size: 1.5rem;
            flex-grow: 1;
            text-align: center;
        }
        header button {
            background-color: #fbc02d;
            color: #3e2723;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }
        header button:hover {
            background-color: #ffd54f;
        }
        form {
            background-color: rgba(255, 235, 204, 0.9);
            padding: 30px;
            max-width: 700px;
            margin: 40px auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        form h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #3e2723;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        form input[type="text"], form input[type="email"], form input[type="tel"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form button {
            background-color: #fbc02d;
            color: #3e2723;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }
        form button:hover {
            background-color: #ffd54f;
        }
        .notification {
            background-color: #dff0d8; /* Success color */
            color: #3c763d;
            padding: 20px;
            margin: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 5px;
            position: relative;
            font-size: 1.1rem;
            width: calc(100% - 40px);
            max-width: 600px; /* Maximum width */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .notification.error {
            background-color: #f2dede; /* Error color */
            color: #a94442;
            border-color: #ebccd1;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-weight: bold;
            cursor: pointer;
            color: inherit;
        }
    </style>
</head>
<body>
    <header>
        <img src="logo.png" alt="Honey Logo">
        <h1>Join Us</h1>
        <div>
            <button onclick="location.href='events.php'">Home</button> <!-- Updated Home button -->
        </div>
    </header>

    <?php if ($message): ?>
        <div class="notification <?php echo $messageType; ?>">
            <button class="close-btn" onclick="this.parentElement.style.display='none';">×</button>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post" id="form" action="">
        <h2>Get Your Ticket</h2>
        <label for="username">Your Name:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required>

        <button type="submit">Take Ticket</button>
    </form>
    
</body>
</html>