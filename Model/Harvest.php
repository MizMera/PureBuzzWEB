<?php
class Harvest {
    private $conn;
    private $table_name = "harvests";

    public $id;
    public $date;
    public $location;
    public $quantity;
    public $quality;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all harvests
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert new harvest
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (date, location, quantity, quality) 
                  VALUES (:date, :location, :quantity, :quality)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':quality', $this->quality);

        return $stmt->execute(); // Return true if successful, false otherwise
    }

    // Update an existing harvest
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET date = :date, location = :location, 
                  quantity = :quantity, quality = :quality WHERE id = :id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':quality', $this->quality);
    
        return $stmt->execute(); // Return true if successful, false otherwise
    }
    

    // Delete a harvest
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        return $stmt->execute(); // Return true if successful, false otherwise
    }
}
?>
