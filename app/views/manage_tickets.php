<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controllers/TicketController.php';

$ticketController = new TicketController($access);

$message = $messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Add Ticket
        if (isset($_POST['add'])) {
            $eventId = $_POST['event_id'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $numTickets = $_POST['num_tickets'];

            if ($ticketController->addTicket($eventId, $username, $phone, $email, $numTickets)) {
                $message = "Ticket added successfully.";
                $messageType = "success";
            } else {
                $message = "Error adding ticket.";
                $messageType = "error";
            }
        }

        // Update Ticket
        elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $numTickets = $_POST['num_tickets'];

            if ($ticketController->updateTicket($id, $username, $phone, $email, $numTickets)) {
                $message = "Ticket updated successfully.";
                $messageType = "success";
            } else {
                $message = "Error updating ticket.";
                $messageType = "error";
            }
        }

        // Delete Ticket
        elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            if ($ticketController->deleteTicket($id)) {
                $message = "Ticket deleted successfully.";
                $messageType = "success";
            } else {
                $message = "Error deleting ticket.";
                $messageType = "error";
            }
        }
    } catch (PDOException $e) {
        $message = "Database Error: " . htmlspecialchars($e->getMessage());
        $messageType = "error";
    }

            // Redirect to avoid form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
}

// Fetch all tickets
$tickets = $ticketController->getAllTickets();
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
            background-color: #fff8e1;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #6d4c41;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffeb3b;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        form {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #8d6e63;
            border-radius: 5px;
            background-color: #fff;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #6d4c41;
        }
        input[type="text"], input[type="tel"], input[type="email"], input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #fbc02d;
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
            background-color: #f9a825;
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
            border: 1px solid #8d6e63;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #fbc02d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Tickets</h1>

        <?php if ($message): ?>
            <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Form to Add Ticket -->
        <form method="POST">
            <h2>Add Ticket</h2>
            <label for="event_id">Event ID:</label>
            <input type="number" name="event_id" id="event_id" required>
            <label for="username">Name:</label>
            <input type="text" name="username" id="username" required>
            <label for="phone">Phone:</label>
            <input type="tel" name="phone" id="phone" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="num_tickets">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets" required min="1">
            <button type="submit" name="add">Add Ticket</button>
        </form>

        <!-- Form to Update Ticket -->
        <form method="POST">
            <h2>Update Ticket</h2>
            <label for="id">Ticket ID:</label>
            <input type="number" name="id" id="id" required>
            <label for="username2">Name:</label>
            <input type="text" name="username" id="username2" required>
            <label for="phone2">Phone:</label>
            <input type="tel" name="phone" id="phone2" required>
            <label for="email2">Email:</label>
            <input type="email" name="email" id="email2" required>
            <label for="num_tickets2">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets2" required min="1">
            <button type="submit" name="update">Update Ticket</button>
        </form>

        <!-- Form to Delete Ticket -->
        <form method="POST">
            <h2>Delete Ticket</h2>
            <label for="delete_id">Ticket ID:</label>
            <input type="number" name="id" id="delete_id" required>
            <button type="submit" name="delete">Delete Ticket</button>
        </form>

        <!-- Display All Tickets -->
        <h2>All Tickets</h2>
        <?php if (!empty($tickets)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Event ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Number of Tickets</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ticket->getId()); ?></td>
                            <td><?php echo htmlspecialchars($ticket->getEventId()); ?></td>
                            <td><?php echo htmlspecialchars($ticket->getUsername()); ?></td>
                            <td><?php echo htmlspecialchars($ticket->getPhone()); ?></td>
                            <td><?php echo htmlspecialchars($ticket->getEmail()); ?></td>
                            <td><?php echo htmlspecialchars($ticket->getNumTickets()); ?></td>
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

