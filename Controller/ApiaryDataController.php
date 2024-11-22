<?php
require_once '../Model/Database.php'; 
require_once '../Model/ApiaryDataModel.php';

class ApiaryController {
    private $apiaryModel;

    public function __construct() {
        $database = new Database();
        $dbConnection = $database->getConnection(); 
        $this->apiaryModel = new Apiary($dbConnection);
    }

    // Fetch all apiaries
    public function getAllApiaries() {
        $apiaries = $this->apiaryModel->getAll(); // Get all apiaries from the model

        echo json_encode($apiaries); // Return data as JSON
    }

    
   
}
?>
