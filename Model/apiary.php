<?php
class Apiary {
    private $conn;
    private $table_name = "apiaries";

    public $id;
    public $apiaryName;
    public $beekeeper;
    public $location;
    public $coordinates;
    public $date;
    public $weather;
    public $hiveCount;
    public $observation;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch all apiaries
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Insert new apiary
    public function create() {
        $query = "INSERT INTO apiaries (apiary_name, beekeeper, location, coordinates, date, weather, hive_count, observation) 
                  VALUES (:apiaryName, :beekeeper, :location, :coordinates, :date, :weather, :hiveCount, :observation)";
        
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':apiaryName', $this->apiaryName);
        $stmt->bindParam(':beekeeper', $this->beekeeper);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':coordinates', $this->coordinates);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':weather', $this->weather);
        $stmt->bindParam(':hiveCount', $this->hiveCount);
        $stmt->bindParam(':observation', $this->observation);

        // Execute the query and return success/failure
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update an apiary
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET
                  apiaryName = :apiaryName,
                  beekeeper = :beekeeper,
                  location = :location,
                  coordinates = :coordinates,
                  date = :date,
                  weather = :weather,
                  hiveCount = :hiveCount,
                  observation = :observation
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':apiaryName', $this->apiaryName);
        $stmt->bindParam(':beekeeper', $this->beekeeper);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':coordinates', $this->coordinates);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':weather', $this->weather);
        $stmt->bindParam(':hiveCount', $this->hiveCount);
        $stmt->bindParam(':observation', $this->observation);

        return $stmt->execute();
    }

    // Delete an apiary
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
?>
