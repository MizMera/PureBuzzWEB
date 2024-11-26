<?php
include_once '../models/claims.php';

class claims
{
    public function create() {}

    public function store()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON input."]);
            return;
        }

        $userName = $data['user_name'] ?? null;
        $product = $data['product'] ?? null;
        $claimDetails = $data['claim_details'] ?? null;

        if (!$userName || !$product || !$claimDetails) {
            http_response_code(400);
            echo json_encode(["error" => "User name, product, and claim details are required."]);
            return;
        }

        $claimModel = new ClaimModel();
        try {
            $claimModel->saveClaim($userName, $product, $claimDetails);
            echo json_encode(["message" => "Claim submitted successfully!"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e]);
        }
    }

    public function list()
    {
        $claimModel = new ClaimModel();
        try {
            $claims = $claimModel->getClaimsAdmin();
            echo json_encode(["claims" => $claims]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to retrieve claims. " . $e->getMessage()]);
        }
    }
    public function update()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON input."]);
            return;
        }

        $claimID = $data['Claim_ID'] ?? null;
        $userName = $data['User_Name'] ?? null;
        $product = $data['Product'] ?? null;
        $details = $data['Details'] ?? null;

        if (!$claimID || !$userName || !$product || !$details) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required. " . $claimID]);
            return;
        }

        $claimModel = new ClaimModel();

        try {
            // Call the model's method to update the claim
            $claimModel->updateClaim($claimID, $userName, $product, $details);
            echo json_encode(["message" => "Claim updated successfully."]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to update claim. " . $e->getMessage()]);
        }
    }
    public function deleteClaim()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data || !isset($data['claim_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request. Claim ID is required."]);
            return;
        }

        $claimID = $data['claim_id'];

        $claimModel = new ClaimModel();

        try {
            $claimModel->deleteClaim($claimID);
            echo json_encode(["message" => "Claim deleted successfully."]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete claim. " . $e->getMessage()]);
        }
    }
}
