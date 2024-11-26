<?php

require_once '../models/responses.php'; // Corrected the path if necessary

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
            echo json_encode(["message" => "Response submitted successfully!"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e]);
        }
    }
}
