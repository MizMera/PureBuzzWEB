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
    public function getResponsesByClaimId()
    {
        $query = "SELECT id, claim_id, response_detail, created_at FROM responses"; // No WHERE condition
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateResponse($responseID, $responseDetail)
    {
        $query = "UPDATE responses SET response_detail = :responseDetail WHERE id = :responseID";
        $statement = $this->db->prepare($query);

        try {
            // Execute with both parameters
            $statement->execute([
                ":responseDetail" => $responseDetail,
                ":responseID" => $responseID, // Ensure you are passing the ID correctly
            ]);

            // Check if any rows were affected
            return $statement->rowCount() > 0; // Return true if the update was successful
        } catch (PDOException $e) {
            // Log the error message for debugging
            error_log("Error updating response: " . $e->getMessage());
            return false; // Indicate failure
        }
    }
    public function deleteResponse($responseID)
    {
        // Prepare the SQL query with a WHERE clause
        $query = "DELETE FROM responses WHERE id = :responseID";

        $stmt = $this->db->prepare($query);
        // Bind the responseID parameter
        $stmt->bindParam(':responseID', $responseID, PDO::PARAM_INT);

        // Execute the statement
        return $stmt->execute(); // Return the result of the execution
    }
}