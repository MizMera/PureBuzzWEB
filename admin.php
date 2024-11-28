<?php
session_start(); // Start the session to use session variables

// Database connection details
$dsn = 'mysql:host=localhost;dbname=purebuzz;charset=utf8mb4';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    // Create a PDO instance
    $access = new PDO($dsn, $username, $password);
    $access->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Initialize variables for feedback messages
$message = '';
$messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new ticket
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        try {
            $stmt = $access->prepare("INSERT INTO tickets (username, phone, email) VALUES (?, ?, ?)");
            $stmt->execute([$name, $phone, $email]);
            $message = "Ticket added successfully!";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Error adding ticket: " . htmlspecialchars($e->getMessage());
            $messageType = "error";
        }
    }

    // Update ticket information
    if (isset($_POST['update'])) {
        $ticketId = $_POST['ticket_id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        try {
            $stmt = $access->prepare("UPDATE tickets SET username = ?, phone = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $email, $ticketId]);
            $message = "Ticket updated successfully!";
            $messageType = "success";
        } catch (PDOException $e) {
            $message = "Error updating ticket: " . htmlspecialchars($e->getMessage());
            $messageType = "error";
        }
    }

    // Delete ticket information
    if (isset($_POST['delete'])) {
        $ticketId = $_POST['ticket_id'];

        try {
            $stmt = $access->prepare("DELETE FROM tickets WHERE id = ?");
            $stmt->execute([$ticketId]);
            $message = "Ticket deleted successfully!";
            $messageType = "success";
            $ticket = null; // Clear the ticket information after deletion
        } catch (PDOException $e) {
            $message = "Error deleting ticket: " . htmlspecialchars($e->getMessage());
            $messageType = "error";
        }
    }
}

// Fetch existing ticket information if ticket_id is provided
$ticket = null;
if (isset($_GET['ticket_id'])) {
    $ticketId = $_GET['ticket_id'];
    $stmt = $access->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->execute([$ticketId]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch all tickets for display
$tickets = [];
try {
    $stmt = $access->query("SELECT * FROM tickets");
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error fetching tickets: " . htmlspecialchars($e->getMessage());
    $messageType = "error";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8e1; /* Light honey background */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #6d4c41; /* Dark brown */
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffeb3b; /* Honey yellow */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #8d6e63; /* Medium brown */
            border-radius: 5px;
            background-color: #fff; /* White background for forms */
        }
        label {
            display: block;
            margin-top: 10px;
            color: #6d4c41; /* Dark brown */
        }
        input[type="text"], input[type="tel"], input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #fbc02d; /* Bright honey yellow */
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
            display: block;
            width: 100%;
        }
        button:hover {
            background-color: #f9a825; /* Darker yellow on hover */
        }
        .success, .error {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #8d6e63; /* Medium brown */
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #fbc02d; /* Bright honey yellow */
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Manage Tickets</h1>

        <?php if ($message): ?>
            <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <h2>Add New Ticket</h2>
            <label for="name">Name:</label>
            <input id="name" type="text" name="name" required>

            <label for="phone">Phone Number:</label>
            <input id="phone" type="tel" name="phone" required>

            <label for="email">Email Address:</label>
            <input id="email" type="email" name="email" required>

            <button type="submit" name="add">Add Ticket</button>
        </form>

        <form method="POST" action="">
            <h2>Update/Delete Ticket</h2>
            <input type="hidden" name="ticket_id" value="<?php echo $ticket ? htmlspecialchars($ticket['id']) : ''; ?>">
            <label for="update_name">Name:</label>
            <input id="update_name" type="text" name="name" value="<?php echo $ticket ? htmlspecialchars($ticket['username']) : ''; ?>" required>

            <label for="update_phone">Phone Number:</label>
            <input id="update_phone" type="tel" name="phone" value="<?php echo $ticket ? htmlspecialchars($ticket['phone']) : ''; ?>" required>

            <label for="update_email">Email Address:</label>
            <input id="update_email" type="email" name="email" value="<?php echo $ticket ? htmlspecialchars($ticket['email']) : ''; ?>" required>

            <button type="submit" name="update">Update Ticket</button>
            <?php if ($ticket): ?>
                <button type="submit" name="delete">Delete Ticket</button>
            <?php endif; ?>
        </form>

        <form method="GET" action="">
            <h2>Find Ticket by ID</h2>
            <label for="ticket_id">Enter Ticket ID:</label>
            <input id="ticket_id" type="number" name="ticket_id" required>
            <button type="submit">Fetch Ticket</button>
        </form>

        <h2>Show All Tickets</h2>
        <?php if (count($tickets) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['id']); ?></td>
                            <td><?php echo htmlspecialchars($t['username']); ?></td>
                            <td><?php echo htmlspecialchars($t['phone']); ?></td>
                            <td><?php echo htmlspecialchars($t['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No tickets found.</p>
        <?php endif; ?>
    </div>
</body>
</html>