<?php
// Include necessary files
include_once __DIR__ . '/../../controllers/ProductController.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductController();
    $controller->store(); // Call the controller's store method to handle the submission
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, textarea, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <h1>Add New Product</h1>

    <form method="POST" action="">
        <!-- Input for Product Name -->
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <!-- Input for Product Price -->
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <!-- Input for Product Stock -->
        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" required>

        <!-- Textarea for Product Description -->
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4"></textarea>

        <!-- Input for Product Image URL -->
        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url">

        <!-- Input for Product Category -->
        <label for="category">Category:</label>
        <input type="text" id="category" name="category">

        <!-- Submit Button -->
        <button type="submit">Save</button>
    </form>
</body>
</html>
