<?php
require_once '../Model/Database.php'; // Include the database connection file
require_once '../Model/Harvest.php';  // Include the Harvest model

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

public function addHarvest() {
    // Check if required POST data is provided
    if (!isset($_POST['date'], $_POST['location'], $_POST['quantity'], $_POST['quality'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        return;
    }

    // Assign the POST data to the model's properties
    $this->harvestModel->date = $_POST['date'];
    $this->harvestModel->location = $_POST['location'];
    $this->harvestModel->quantity = $_POST['quantity'];
    $this->harvestModel->quality = $_POST['quality'];

    // Attempt to create the record
    if ($this->harvestModel->create()) {
        echo json_encode(['success' => true, 'message' => 'Harvest added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding harvest']);
    }
}


    // Delete a harvest record
    public function deleteHarvest() {
        $data = json_decode(file_get_contents("php://input"), true); // Capture the data for DELETE
        if (isset($data['id'])) {
            $this->harvestModel->id = $data['id'];
            $result = $this->harvestModel->delete();
            echo json_encode(['success' => $result, 'message' => $result ? 'Record deleted successfully' : 'Failed to delete record']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID is required for deletion']);
        }
    }

    // Edit an existing harvest record
    public function editHarvest() {
        $data = json_decode(file_get_contents("php://input"), true); // Capture JSON body
        if (isset($data['id'], $data['date'], $data['location'], $data['quantity'], $data['quality'])) {
            $this->harvestModel->id = $data['id'];
            $this->harvestModel->date = $data['date'];
            $this->harvestModel->location = $data['location'];
            $this->harvestModel->quantity = $data['quantity'];
            $this->harvestModel->quality = $data['quality'];

            $result = $this->harvestModel->update();
            echo json_encode(['success' => $result, 'message' => $result ? 'Record updated successfully' : 'Failed to update record']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
        }
    }
}
?>
