<?php

include __DIR__. '/../config/database.php';
include __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;
    private $conn;
    
    public function __construct() {
        // Initialize the Database connection
        $database = new Database();
        $this->conn = $database->getConnexion();

        // Initialize the Product model
        $this->productModel = new Product($this->conn);
    }


    public function getAllProducts() {
        $db = Database::getConnexion();
        $query = $db->prepare("SELECT * FROM products");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function index() {
        $products = $this->productModel->getAll();
        include __DIR__ . '/../views/Products/index.php';
    }

    public function create() {
        include __DIR__ . '/../views/Products/create.php';
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle image upload
            $image_url = null;  // Default image URL to null
    
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Validate file type (optional)
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
    
                if (in_array($fileType, $allowedTypes)) {
                    // Generate a unique filename
                    $image_name = uniqid('product_', true) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    
                    // Define upload directory (ensure this directory is writable)
                    $uploadDir = __DIR__ . '/../uploads/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
    
                    // Move the file to the upload directory
                    $uploadPath = $uploadDir . $image_name;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Store the image URL (relative path)
                        $image_url = '/uploads/' . $image_name;
                    } else {
                        // Handle upload error
                        echo "Error uploading the file.";
                        exit;
                    }
                } else {
                    echo "Invalid file type.";
                    exit;
                }
            }
    
            // Prepare other product data
            $data = [
                'name' => $_POST['name'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock'],
                'description' => $_POST['description'],
                'image_url' => $image_url,
                'category' => $_POST['category']
            ];
    
            // Save product to database
            $this->productModel->create($data);
            header('Location: index.php?controller=Product&action=index');
        }
    }

    public function edit($id) {
        $product = $this->productModel->getById($id);
        include __DIR__ . '/../views/Products/edit.php';
    }
    public function getProductById($id) {
        // Fetch product by ID from the database
        $db = Database::getConnexion();
        $query = $db->prepare("SELECT * FROM products WHERE id = :id");
        $query->execute(['id' => $id]);
        $product = $query->fetch(PDO::FETCH_ASSOC);
    
        // Debugging: Check if product is found
        if ($product) {
            return $product;
        } else {
            // Debugging: Output error if no product is found
            echo "No product found with ID: " . htmlspecialchars($id);
            return null;
        }
    }
    public function updateProduct($id, $name, $price, $stock, $description, $image_url, $category) {
        $query = "UPDATE products 
                  SET name = :name, price = :price, stock = :stock, description = :description, image_url = :image_url, category = :category 
                  WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the values to the statement
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':category', $category);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
   
    public function delete($id) {
        $this->productModel->delete($id);
        header('Location: index.php?controller=Product&action=index'); // Redirect after deletion
        exit();
    }
    
}

