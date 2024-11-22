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

}
?>
