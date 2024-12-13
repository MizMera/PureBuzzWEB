<?php


class categorie {
    private $id;
    private $name;
    private $description;
    private $db;
    // Constructor
    public function __construct($name = null, $description = null) {
        $this->db = Database::getConnexion(); // Initialize database connection
        $this->name = $name;
        $this->description = $description;
    }
    // Create a new categorie
    public function create($data) {
        $query = "INSERT INTO categories (name, description) 
                  VALUES (:name, :description)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    

    public function getCategories() {
        $db = Database::getConnexion();
        if ($this->db === null) {
            die("Database connection is not initialized.");
        }
        $query = $db->prepare("SELECT * FROM categories");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Read a single categorie by ID
    public function getById($id) {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Debugging output
            if (!$result) {
                echo "Debug: No category found for ID " . htmlspecialchars($id);
            }
    
            return $result;
        } catch (Exception $e) {
            echo "Database error: " . $e->getMessage();
            return null;
        }
    }

    // Delete a categorie
    public function delete($id) {
        // Check for related products
        $query = "SELECT COUNT(*) AS count FROM products WHERE category_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result['count'] > 0) {
            throw new Exception("Cannot delete category. It is referenced by {$result['count']} product(s).");
        }
    
        // Proceed with deletion if no related records
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $id]);
    }
    

    public function updateCategorie($id, $name, $description) {
        $query = "UPDATE categories 
                  SET name = :name, description = :description 
                  WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }
 
    // Getters and setters
    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getDescription() {
        return $this->description;
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
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }
    

}


