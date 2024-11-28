<?php
include_once '../config/database.php';

class ClaimModel
{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    public function saveClaim($userName, $product, $details)
    {
        // Updated query without claim_date and status
        // $query = "INSERT INTO claims (user_id, order_id, details) 
        //           VALUES ((SELECT id FROM users WHERE name = ? LIMIT 1), 
        //                   (SELECT id FROM orders WHERE name = ? LIMIT 1), 
        //                   ?)";
        $query = "INSERT INTO claims (user_id, order_id, details) 
          VALUES (
              (SELECT id FROM users WHERE email = ? LIMIT 1), 
              (SELECT id FROM orders WHERE name = ? LIMIT 1), 
              ?)";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$userName, $product, $details]);
    }


    public function getClaimsAdmin()
    {
        $query = "
        SELECT 
            claims.id AS 'Claim_ID', 
            claims.details AS 'Details',
            claims.created_at AS Created_At, 
            users.email AS 'User_Name', 
            orders.name AS 'Product'
        FROM 
            claims
        JOIN 
            users ON claims.user_id = users.id
        JOIN 
            orders ON claims.order_id = orders.id
            
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateClaim($claimId, $userName, $product, $claimDetails)
    {
        $CI = (int)$claimId;
        $query = "UPDATE claims 
                  SET user_id = (SELECT id FROM users WHERE email = ? LIMIT 1), 
                      order_id = (SELECT id FROM orders WHERE name = ? LIMIT 1), 
                      details = ?
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        if (!$stmt->execute([$userName, $product, $claimDetails, $CI])) {
            throw new Exception("Failed to update claim.");
        }
    }
    public function deleteClaim($claimID)
    {
        $id = (int)$claimID;
        $query = "DELETE FROM claims WHERE id = :claimID";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':claimID', $id);

        $stmt->execute();
    }
    public function searchProd($product)
    {
        $query = "SELECT *FROM orders WHERE name =? ;";
        $stmt = $this->db->prepare($query);
        if ((!$stmt->execute([$product]))) {
            throw new Exception("failed to load products");
        }
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
