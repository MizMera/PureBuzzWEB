<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Tickets - PureBuzz Honey Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff8e1;
            color: #3e2723;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 235, 204, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #3e2723;
            text-align: center;
        }
        form {
            display: grid;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #d7ccc8;
            border-radius: 4px;
        }
        button {
            background-color: #fbc02d;
            color: #3e2723;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #f9a825;
        }
        .notification {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .success {
            background-color: #c8e6c9;
            color: #2e7d32;
        }
        .error {
            background-color: #ffcdd2;
            color: #c62828;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Get Your Tickets</h1>
        <form action="index.php?action=processTicket" method="post">
            <label for="event">Select Event:</label>
            <select name="event_id" id="event" required>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event->getId() ?>"><?= htmlspecialchars($event->getName()) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="num_tickets">Number of Tickets:</label>
            <input type="number" id="num_tickets" name="num_tickets" min="1" required>

            <button type="submit">Reserve Tickets</button>
        </form>
        <?php if (isset($notification)): ?>
            <div class="notification <?= $notification['type'] ?>">
                <?= $notification['message'] ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

