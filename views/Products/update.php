<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the necessary files
include_once __DIR__ . '/../../controllers/ProductController.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the necessary fields are provided
    if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['stock']) && isset($_POST['description']) && isset($_POST['image_url']) && isset($_POST['category'])) {
        
        // Retrieve the form data
        $id = $_POST['id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $description = $_POST['description'];
        $image_url = $_POST['image_url'];
        $category = $_POST['category'];

        // Create an instance of ProductController to update the product
        $productController = new ProductController();
        
        // Call the updateProduct method from the controller
        $updated = $productController->updateProduct($id, $name, $price, $stock, $description, $image_url, $category);

        if ($updated) {
            echo "Product updated successfully.";
            // Redirect to the product list or another page if needed
            header('Location: index.php'); // Adjust the redirection as needed
            exit;
        } else {
            echo "Failed to update the product.";
        }
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request.";
}
?>
