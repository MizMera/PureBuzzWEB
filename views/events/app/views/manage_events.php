<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../../../controllers/EventController.php';

$eventController = new EventController($access);

$message = $messageType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Add Event
        if (isset($_POST['add'])) {
            $name = $_POST['name'];
            $location = $_POST['location'];
            $description = $_POST['description'];
            $numTickets = $_POST['num_tickets'];

            $uploadDir = 'C:\xampp\htdocs\dashboard\karamvc\public';
            $imageFile = basename($_FILES['image']['name']);
            $imagePath = $uploadDir . $imageFile;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                if ($eventController->addEvent($name, $location, $description, $numTickets, $imagePath)) {
                    $message = "Event added successfully.";
                    $messageType = "success";
                } else {
                    $message = "Error adding event.";
                    $messageType = "error";
                }
            } else {
                $message = "Error uploading image.";
                $messageType = "error";
            }
        }

        // Update Event
        elseif (isset($_POST['update'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $location = $_POST['location'];
            $description = $_POST['description'];
            $numTickets = $_POST['num_tickets'];

            if ($eventController->updateEvent($id, $name, $location, $description, $numTickets)) {
                $message = "Event updated successfully.";
                $messageType = "success";
            } else {
                $message = "Error updating event.";
                $messageType = "error";
            }
        }

        // Delete Event
        elseif (isset($_POST['delete'])) {
            $id = $_POST['id'];
            if ($eventController->deleteEvent($id)) {
                $message = "Event deleted successfully.";
                $messageType = "success";
            } else {
                $message = "Error deleting event.";
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

// Fetch all events
$events = $eventController->getAllEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beez & Honey Event Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff8e1;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #6d4c41;
            margin-bottom: 30px;
        }
        .container {
            max-width: 800px;
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
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="file"] {
            display: none;
        }
        .file-upload {
            display: inline-block;
            background-color: #fbc02d;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 5px;
        }
        .file-upload:hover {
            background-color: #f9a825;
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
        }
        button:hover {
            background-color: #f9a825;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #ffe082;
            color: #6d4c41;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Beez & Honey Event Management</h1>

        <?php if ($message): ?>
            <p class="<?php echo $messageType; ?>"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Form to Add Event -->
        <form method="POST" enctype="multipart/form-data">
            <h2>Add Event</h2>
            <label for="name">Name:</label>
            <input id="name" type="text" name="name" required>
            <label for="location">Location:</label>
            <input type="text" name="location" id="location" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="num_tickets">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets" required min="1">
            <label for="file-upload" class="file-upload">Choose File</label>
            <input id="file-upload" type="file" name="image" accept="image/*" required>
            <button type="submit" name="add">Add Event</button>
        </form>

        <!-- Form to Update Event -->
        <form method="POST">
            <h2>Update Event</h2>
            <label for="id">Event ID:</label>
            <input type="number" name="id" id="id" required>
            <label for="name2">Name:</label>
            <input type="text" name="name" id="name2" required>
            <label for="location2">Location:</label>
            <input type="text" id="location2" name="location" required>
            <label for="description2">Description:</label>
            <textarea name="description" id="description2" required></textarea>
            <label for="num_tickets2">Number of Tickets:</label>
            <input type="number" name="num_tickets" id="num_tickets2" required min="1">
            <button type="submit" name="update">Update Event</button>
        </form>

        <!-- Form to Delete Event -->
        <form method="POST">
            <h2>Delete Event</h2>
            <label for="delete_id">Event ID:</label>
            <input type="number" name="id" id="delete_id" required>
            <button type="submit" name="delete">Delete Event</button>
        </form>

        <!-- Display All Events -->
        <h2>All Events</h2>
        <?php if (!empty($events)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Number of Tickets</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($event->getId()); ?></td>
                            <td><?php echo htmlspecialchars($event->getName()); ?></td>
                            <td><?php echo htmlspecialchars($event->getLocation()); ?></td>
                            <td><?php echo htmlspecialchars($event->getDescription()); ?></td>
                            <td><?php echo htmlspecialchars($event->getNumTickets()); ?></td>
                            <td><img src="<?php echo htmlspecialchars($event->getImage()); ?>" alt="Event Image" style="width:100px;"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</body>
</html>

