
<?php

require_once '../models/responses.php';
require_once '../models/claims.php';
 // Corrected the path if necessary
require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';
class Responses
{
    private $responseModel;

    public function __construct()
    {
        // Instantiate the ResponseModel class
        $this->responseModel = new ResponseModel();
    }

    public function store_response()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON input."]);
            return;
        }

        $claimId = $data['claim_id'];
        $responseDetails = $data['response_details'] ?? null;

        if (!$responseDetails || !$claimId) {
            http_response_code(400);
            echo json_encode(["error" => "User name, product, and claim details are required."]);
            return;
        }

        $ResponseModel = new ResponseModel();
        try {
            $ResponseModel->saveResponse($claimId, $responseDetails);
            echo json_encode(["message" => "Response submitted successfully! an email is sent to the user"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e]);
        }
    }
    
    
    public function list_response()
    {
        $ResponseModel = new ResponseModel();
        try {
            $response = $ResponseModel->getResponsesByClaimId();
            echo json_encode(["responses" => $response]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to retrieve claims. " . $e->getMessage()]);
        }
    }
    public function update_response()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON input."]);
            return;
        }


        $responseID = ltrim($data['Response_ID'], '#'); // Remove '#' if present
        $responseDetail = $data['Response_Detail'] ?? null;

        if (!$responseID || !$responseDetail) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required. Response ID or Response Detail is missing."]);
            return;
        }

        $responseModel = new ResponseModel();

        try {

            if ($responseModel->updateResponse($responseID, $responseDetail)) {
                echo json_encode(["message" => "Response updated successfully."]);
            } else {
        
                http_response_code(404);
                echo json_encode(["error" => "Response not found or no changes made."]);
            }
        } catch (Exception $e) {
    
            error_log("Error in update_response: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => "Failed to update response. " . $e->getMessage()]);
        }
    }
    public function deleteResponse()
    {
        $data = json_decode(file_get_contents("php://input"), true);

    
        if (!$data || !isset($data['response_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request. Response ID is required."]);
            return;
        }

        $responseID = $data['response_id'];

        $responseModel = new ResponseModel();

        try {
            if ($responseModel->deleteResponse($responseID)) {
                echo json_encode(["message" => "Response deleted successfully."]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Response not found."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete response. " . $e->getMessage()]);
        }
    }
}
