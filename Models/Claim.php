<?php
class Claim
{
    private $db;
    public $id;
    public $user_id;
    public $product;
    public $claim_details;
    public $claim_date;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function create($user_id, $product, $claim_details, $claim_date)
    {
        $this->user_id = $user_id;
        $this->product = $product;
        $this->claim_details = $claim_details;
        $this->claim_date = $claim_date;

        $query = "INSERT INTO claims (user_id, product, claim_details, claim_date)
              VALUES (:user_id, :product, :claim_details, :claim_date)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':product', $this->product);
        $stmt->bindParam(':claim_details', $this->claim_details);
        $stmt->bindParam(':claim_date', $this->claim_date);

        if ($stmt->execute()) {
            return true;
        } else {
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . $errorInfo[2]);
            return false;
        }
    }

    public function getAllClaims()
    {
        $query = "SELECT * FROM claims";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClaimsByUser($user_id)
    {
        $query = "SELECT * FROM claims WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($claim_id)
    {
        // Example update method, adjust as needed
        $query = "UPDATE claims SET product = :product, claim_details = :claim_details,
                  claim_date = :claim_date WHERE id = :claim_id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':product', $this->product);
        $stmt->bindParam(':claim_details', $this->claim_details);
        $stmt->bindParam(':claim_date', $this->claim_date);
        $stmt->bindParam(':claim_id', $claim_id);

        return $stmt->execute();
    }

    public function delete($claim_id)
    {
        $query = "DELETE FROM claims WHERE id = :claim_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':claim_id', $claim_id);
        return $stmt->execute();
    }
}
