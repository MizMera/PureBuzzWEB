<?php
// Include necessary files
include_once __DIR__ . '/../../controllers/categories_controller.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categorie_Controller = new categorie_Controller();
    $categorie_Controller->store(); // Call the controller's store method to handle the submission
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Categorie</title>
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
    <h1> Add Categorie</h1>

    <div class="form-card" >
                <h2>Add Product</h2>
            <form id="productForm" method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter product name" required>
                    <span id="nameError" class="error"></span>
                </div> 
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4"></textarea>
                    <span id="descriptionError" class="error"></span>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option>Honey</option>
                        <option>Medicines</option>
                        <option>Cosmetics</option>
                        <option>Beehives</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary">Save Product</button>
                </form>
    </div>
</body>
</html>
