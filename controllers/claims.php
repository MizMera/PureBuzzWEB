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
}
