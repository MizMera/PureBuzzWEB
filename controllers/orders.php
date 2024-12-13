<?php
include_once '../models/orders.php';

class orders {
    public function storeFeedback() {
        header('Content-Type: application/json'); // Ensure the response is JSON
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON input."]);
            return;
        }

        $productName = $data['product_name'] ?? null; // Product name
        $comment = $data['comment'] ?? null; // User comment
        $rating = $data['rating'] ?? null;  // User rating

        if (!$productName || !$comment || !$rating) {
            http_response_code(400);
            echo json_encode(["error" => "Product name, rating, and comment are required for submission."]);
            return;
        }

        $ratesModel = new RatesModel();
        try {
            // Save the feedback
            $ratesModel->saveFeedback($productName, $rating, $comment);

            echo json_encode(["message" => "Your feedback has been successfully submitted."]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }
    public function listFeedbacks()
    {
        $claimModel = new RatesModel();
        try {
            $claims = $claimModel->getFeedback();
            echo json_encode(["feedbacks" => $claims]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to retrieve feedbacks. " . $e->getMessage()]);
        }
    }
}