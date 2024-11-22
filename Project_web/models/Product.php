<?php


class Product {
    private $id;
    private $name;
    private $price;
    private $stock;
    private $description;
    private $image_url;
    private $category;
    private $created_at;
    private $updated_at;
    private $db;
    // Constructor
    public function __construct($name = null, $price = null, $stock = null, $description = null, $image_url = null, $category = null) {
        $this->db = Database::getConnexion(); // Initialize database connection
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->category = $category;
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');
    }
    // Create a new product
    public function create($data) {
        $query = "INSERT INTO products (name, price, stock, description, image_url, category, created_at, updated_at) 
                  VALUES (:name, :price, :stock, :description, :image_url, :category, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    

    // Read all products
    public function getAll() {
        if ($this->db === null) {
            die("Database connection is not initialized.");
        }
    
        $query = "SELECT * FROM products"; // SQL query to fetch all products
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC); // Execute and fetch data
    }
    

    // Read a single product by ID
    public function getById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Delete a product
    public function delete($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
    // Getters and setters
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getStock() {
        return $this->stock;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getImageUrl() {
        return $this->image_url;
    }
    public function getCategory() {
        return $this->category;
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    // Setters (with chaining)
    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
    public function setPrice($price) {
        $this->price = $price;
        return $this;
    }
    public function setStock($stock) {
        $this->stock = $stock;
        return $this;
    }
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    public function setImageUrl($image_url) {
        $this->image_url = $image_url;
        return $this;
    }
    public function setCategory($category) {
        $this->category = $category;
        return $this;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
        return $this;
    }
    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
        return $this;
    }
    

}


