<?php
require_once '../Model/Database.php'; 
require_once '../Model/Apiary.php';

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

    
   // Add a new apiary
   public function addApiary() {
    if (!isset($_POST['apiaryName'], $_POST['beekeeper'], $_POST['location'], $_POST['coordinates'], $_POST['date'], $_POST['weather'], $_POST['hiveCount'], $_POST['observation'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        return;
    }

    // Assign POST data to model's properties
    $this->apiaryModel->apiaryName = $_POST['apiaryName'];
    $this->apiaryModel->beekeeper = $_POST['beekeeper'];
    $this->apiaryModel->location = $_POST['location'];
    $this->apiaryModel->coordinates = $_POST['coordinates'];
    $this->apiaryModel->date = $_POST['date'];
    $this->apiaryModel->weather = $_POST['weather'];
    $this->apiaryModel->hiveCount = $_POST['hiveCount'];
    $this->apiaryModel->observation = $_POST['observation'];

    // Insert the new apiary
    if ($this->apiaryModel->create()) {
        echo json_encode(['success' => true, 'message' => 'Apiary added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding apiary']);
    }
}

    
    
    // Delete a harvest record
    public function deleteApiary() {
        $data = json_decode(file_get_contents("php://input"), true); // Capture the data for DELETE
        if (isset($data['id'])) {
            $this->apiaryModel->id = $data['id'];
            $result = $this->apiaryModel->delete();
            echo json_encode(['success' => $result, 'message' => $result ? 'Record deleted successfully' : 'Failed to delete record']);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID is required for deletion']);
        }
    }

    // Edit an existing harvest record
    public function editApiary() {
        $data = json_decode(file_get_contents("php://input"), true); // Capture JSON body
        if (isset($data['id'], $data['apiaryName'], $data['beekeeper'], $data['location'], $data['coordinates'], $data['date'], $data['weather'], $data['hiveCount'], $data['observation'])) {
            $this->apiaryModel->id = $data['id'];
    $this->apiaryModel->apiaryName = $data['apiaryName'];
    $this->apiaryModel->beekeeper = $data['beekeeper'];
    $this->apiaryModel->location = $data['location'];
    $this->apiaryModel->coordinates = $data['coordinates'];
    $this->apiaryModel->date = $data['date'];
    $this->apiaryModel->weather = $data['weather'];
    $this->apiaryModel->hiveCount = $data['hiveCount'];
    $this->apiaryModel->observation = $data['observation'];

            $result = $this->apiaryModel->update();
            echo json_encode(['success' => $result, 'message' => $result ? 'Record updated successfully' : 'Failed to update record']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
        }
    }
}
?>
