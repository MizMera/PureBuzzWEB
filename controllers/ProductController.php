<?php

include __DIR__. '/../config/database.php';
include __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;
    private $conn;
    private $sortDirection;
    private $sortField;

    public function __construct() {
        // Initialize the Database connection
        $database = new Database();
        $this->conn = $database->getConnexion();

        // Initialize the Product model
        $this->productModel = new Product($this->conn);
    }


    public function getAllProducts($sortColumn = 'id', $sortDirection = 'ASC') {
        $allowedColumns = ['id', 'name', 'price', 'stock', 'description', 'category_id'];
        $allowedDirections = ['ASC', 'DESC'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
        if (!in_array($sortDirection, $allowedDirections)) {
            $sortDirection = 'ASC';
        }
        
    
        $query = "SELECT * FROM products ORDER BY $sortColumn $sortDirection";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                'category_id' => $_POST['category_id']
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


    
    public function updateProduct($productId, $name, $price, $stock, $description, $imageUrl, $categoryId) {
        // Check if a new image file has been uploaded
        if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === 0) {
            // Get file details
            $fileTmpPath = $_FILES['image_url']['tmp_name'];
            $fileName = uniqid() . '_' . $_FILES['image_url']['name']; // Unique file name to avoid overwriting
    
            // Define the upload directory
            $uploadDir = __DIR__ . '/../uploads/';
    
            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            // Define the target file path
            $uploadFilePath = $uploadDir . basename($fileName);
    
            // Validate file type (e.g., images only)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['image_url']['type'], $allowedTypes)) {
                echo "Error: Invalid file type.";
                return;
            }
    
            // Validate file size (e.g., max 2MB)
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            if ($_FILES['image_url']['size'] > $maxFileSize) {
                echo "Error: File size exceeds the 2MB limit.";
                return;
            }
    
            // Move the uploaded file to the upload directory
            if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                // Save the relative path for the image
                $imageUrl = '/uploads/' . basename($fileName);
            } else {
                echo "Error: Failed to upload the file.";
                return;
            }
        }
    
        // Update the product information in the database, including the image URL
        $query = "UPDATE products 
                  SET name = :name, price = :price, stock = :stock, description = :description, image_url = :image_url, category_id = :category_id 
                  WHERE id = :product_id";
    
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->bindParam(':product_id', $productId);
    
        // Execute the query to update the product
        if ($stmt->execute()) {
            echo "Product updated successfully!";
        } else {
            echo "Error: Failed to update the product.";
        }
    }
    

    
    
   
    public function delete($id) {
        $this->productModel->delete($id);
        header('Location: index.php?controller=Product&action=index'); // Redirect after deletion
        exit();
    }
    
    public function search($term) {
        require_once __DIR__ . '/../models/Product.php';
        $productModel = new Product();
    
        return $productModel->searchProducts($term);
    }
    
}

