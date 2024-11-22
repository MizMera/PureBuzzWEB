<?php
require_once '../Model/Database.php'; // Include the database connection file
require_once '../Model/HarvestDataModel.php';  // Include the Harvest model

class HarvestController {
    private $harvestModel;

    public function __construct() {
        // Create a new Database instance and get the connection
        $database = new Database();
        $dbConnection = $database->getConnection(); // Get the actual database connection

        // Pass the connection to the Harvest model
        $this->harvestModel = new Harvest($dbConnection);
    }

    // Get all harvest records
    public function getAllHarvests() {
        $harvests = $this->harvestModel->getAll();
        echo json_encode($harvests); // Return data as JSON
    }

    // Add a new harvest record


}
?>
