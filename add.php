<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beez & Honey Event Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fff8e1; /* Light honey background */
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #6d4c41; /* Dark brown */
            margin-bottom: 30px;
        }
        .container {
            max-width: 800px;
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
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        /* Hide the default file input */
        input[type="file"] {
            display: none;
        }
        /* Custom button for file input */
        .file-upload {
            display: inline-block;
            background-color: #fbc02d; /* Bright honey yellow */
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 5px;
        }
        .file-upload:hover {
            background-color: #f9a825; /* Darker yellow on hover */
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
        }
        button:hover {
            background-color: #f9a825; /* Darker yellow on hover */
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
            background-color: #ffe082; /* Light honey color */
            color: #6d4c41; /* Dark brown */
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

        <!-- Form to Add Event -->
        <form method="POST" enctype="multipart/form-data">
            <h2>Add Event</h2>
            <label>Name:</label>
            <input id="name" type="text" name="name" required>
            <label>Location:</label>
            <input type="text" name="location" id="location" required>
            <label>Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label>Image:</label>
            <label for="file-upload" class="file-upload">Choose File</label>
            <input id="file-upload" type="file" name="image" accept="image/*" required>
            <button type="submit" name="add">Add Event</button>
        </form>

        <!-- Form to Update Event -->
        <form method="POST">
            <h2>Update Event</h2>
            <label>Event ID:</label>
            <input type="number" name="id" id="iddd" required>
            <label>Name:</label>
            <input type="text" name="name" id="name2" required>
            <label>Location:</label>
            <input type="text" id="location2" name="location2" required>
            <label>Description:</label>
            <textarea name="description" id="description2" required></textarea>
            <button type="submit" name="update">Update Event</button>
        </form>

        <!-- Form to Delete Event -->
        <form method="POST">
            <h2>Delete Event</h2>
            <label>Event ID:</label>
            <input type="number" name="id" required>
            <button type="submit" name="delete">Delete Event</button>
        </form>

        <!-- Form to Show All Events -->
        <form method="POST">
            <h2>Show All Events</h2>
            <button type="submit" name="show_all">Show Events</button>
        </form>

        <?php
        require("connexion.php");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            try {
                // Add Event
                if (isset($_POST['add'])) {
                    $name = $_POST['name'];
                    $location = $_POST['location'];
                    $description = $_POST['description'];

                    $imagePath = 'images/' . basename($_FILES['image']['name']);

                    // Check for upload errors
                    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                        echo "<p class='error'>Error uploading image. Error Code: " . $_FILES['image']['error'] . "</p>";
                    } else {
                        // Attempt to move the uploaded file
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                            // Insert event data with image path
                            $stmt = $access->prepare("INSERT INTO events (name, location, description, img) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$name, $location, $description, $imagePath]);
                            echo "<p class='success'>Event added successfully.</p>";
                        } else {
                            echo "<p class='error'>Error moving uploaded file.</p>";
                        }
                    }
                }

                // Update Event
                elseif (isset($_POST['update'])) {
                    $id = $_POST['id'];
                    $name = $_POST['name'];
                    $location = $_POST['location2'];
                    $description = $_POST['description'];

                    $stmt = $access->prepare("UPDATE events SET name=?, location=?, description=? WHERE id=?");
                    $stmt->execute([$name, $location, $description, $id]);
                    echo "<p class='success'>Event updated successfully.</p>";
                }

                // Delete Event
                elseif (isset($_POST['delete'])) {
                    $id = $_POST['id'];
                    $stmt = $access->prepare("DELETE FROM events WHERE id=?");
                    $stmt->execute([$id]);
                    echo "<p class='success'>Event deleted successfully.</p>";
                }

                // Show All Events
                elseif (isset($_POST['show_all'])) {
                    $stmt = $access->query("SELECT id, name, location, description FROM events"); // Removed img from selection
                    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($events) > 0) {
                        echo "<h2>All Events</h2>";
                        echo "<table>";
                        echo "<tr><th>ID</th><th>Name</th><th>Location</th><th>Description</th></tr>";
                        foreach ($events as $event) {
                            echo "<tr>
                                <td>" . htmlspecialchars($event['id']) . "</td>
                                <td>" . htmlspecialchars($event['name']) . "</td>
                                <td>" . htmlspecialchars($event['location']) . "</td>
                                <td>" . htmlspecialchars($event['description']) . "</td>
                            </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No events found.</p>";
                    }
                }
            } catch (PDOException $e) {
                echo "<p class='error'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        ?>
        <script src="add.js"></script>
    </div>
</body>
</html>