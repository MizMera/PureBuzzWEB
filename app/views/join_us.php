<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../controllers/EventController.php';
require_once __DIR__ . '/../controllers/TicketController.php';

$eventController = new EventController($access);
$ticketController = new TicketController($access);

$events = $eventController->getAllEvents();

$message = $messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = $_POST['event_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $numTickets = $_POST['num_tickets'];

    if ($ticketController->addTicket($eventId, $name, $phone, $email, $numTickets)) {
        $message = "Ticket(s) added successfully!";
        $messageType = "success";
    } else {
        $message = "Failed to add ticket(s). Please try again.";
        $messageType = "error";
    }
            // Redirect to avoid form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ticket</title>
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

        header h1 {
            color: #fff8e1;
            margin: 0;
            font-size: 1.5rem;
            text-align: center;
            flex-grow: 1;
        }

        form {
            background-color: rgba(255, 235, 204, 0.9);
            padding: 30px;
            max-width: 400px;
            margin: 50px auto;
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
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #fbc02d;
            color: #3e2723;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #ffd54f;
        }

        .notification {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 5px;
            display: <?php echo isset($message) ? 'block' : 'none'; ?>;
        }

        .notification.error {
            background-color: #f2dede;
            color: #a94442;
            border-color: #ebccd1;
        }
    </style>
</head>
<body>

<header>
    <h1>Add Ticket</h1>
</header>

<div class="notification <?php echo isset($messageType) ? $messageType : ''; ?>">
    <?php echo isset($message) ? $message : ''; ?>
</div>

<form method="post" action="index.php?action=processTicket">
    <h2>Your Details</h2>

    <label for="event_id">Select Event:</label>
    <select id="event_id" name="event_id" required>
        <option value="">-- Select an Event --</option>
        <?php foreach ($events as $event): ?>
            <option value="<?php echo $event->getId(); ?>"><?php echo htmlspecialchars($event->getName()); ?> (Available: <?php echo $event->getNumTickets(); ?>)</option>
        <?php endforeach; ?>
    </select>

    <label for="name">Your Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="num_tickets">Number of Tickets:</label>
    <input type="number" id="num_tickets" name="num_tickets" min="1" required>

    <button type="submit" name="add">Add Ticket</button>
</form>

</body>
</html>

