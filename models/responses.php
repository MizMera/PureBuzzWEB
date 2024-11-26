<?php

require_once '../config/database.php';

class ResponseModel
{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase(); // Ensure this function connects to your DB
    }

    public function saveResponse($claimId, $responseDetail)
    {
        // Insert a response linked to the specified claim_id directly
        $query = "INSERT INTO responses (claim_id, response_detail) VALUES (?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$claimId, $responseDetail]);
            return $stmt->rowCount() > 0; // Return true if the row was inserted
        } catch (PDOException $e) {
            error_log("Error saving response: " . $e->getMessage()); // Log error for debugging
            return false; // Return false on failure
        }
    }
}
