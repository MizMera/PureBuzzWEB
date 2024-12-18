<?php

require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../models/categories.php';

class categorie_Controller {
    private $categories_model;
    private $conn;
    
    public function __construct() {
        // Initialize the Database connection
        $database = new Database();
        $this->conn = $database->getConnexion();
        
        // Initialize the categorie model
        $this->categories_model = new Categorie($this->conn);
    }


    public function getCategories() {
        return $this->categories_model->getCategories();
    }

    

    public function index() {
        // Fetch all categories using the model
        $categories = $this->categories_model->getCategories();
        include __DIR__ . '/../views/categories/index.php';
    }

    public function create() {
        include __DIR__ . '/../views/categories/create.php';
    }
    public function store() {
        
            
            
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prepare other CATEGOREIS data
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
            ];
            // Save CATEGOREIS to database
            $this->categories_model->create($data);
            header('Location: index.php?controller=categories&action=index');
        }
    }

    public function edit($id) {
        if (empty($id)) {
            echo "No ID provided.";
            exit;
        }
    
        $categorie = $this->categories_model->getById($id);
    
        if (!$categorie) {
            echo "Category with ID " . htmlspecialchars($id) . " not found.";
            exit;
        }
    
        include __DIR__ . '/../views/categories/edit.php';
    }
    public function getcategoriesById($id) {
        // Fetch categorie by ID from the database
        $db = Database::getConnexion();
        $query = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $query->execute(['id' => $id]);
        $categorie = $query->fetch(PDO::FETCH_ASSOC);
    
        // Debugging: Check if categories is found
        if ($categorie) {
            return $categorie;
        } else {
            // Debugging: Output error if no categorie is found
            echo "No categorie found with ID: " . htmlspecialchars($id);
            return null;
        }
    }
    
    public function updatecategorie($id,$name,$description) {
        $query = "UPDATE categories 
                  SET name = :name, description =:description 
                  WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind the values to the statement
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        // Execute the query and check if it was successful
        if ($stmt->execute()) {
            return true;
        } 
        return false;
    }
    
   
    public function delete($id) {
        try {
            $this->categories_model->delete($id);
            header('Location: index.php?controller=categorie&action=index&message=success');
        } catch (Exception $e) {
            header('Location: index.php?controller=categorie&action=index&message=error&error=' . urlencode($e->getMessage()));
        }
        exit();
    }
    
}

