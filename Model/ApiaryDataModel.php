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

    
}
?>
