<?php
include_once '../models/Claim.php';  // Ensure the Claim model is included

class ClaimController
{
    private $db;
    private $claimModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->claimModel = new Claim($db);
    }

    public function addClaim($user_id, $product, $claim_detail, $claim_date)
    {
        $this->claimModel->user_id = $user_id;
        $this->claimModel->product = $product;
        $this->claimModel->claim_details = $claim_detail; // Correct property name
        $this->claimModel->claim_date = $claim_date;

        // Debugging output
        echo "User ID: " . $this->claimModel->user_id . "<br>";
        echo "Product: " . $this->claimModel->product . "<br>";
        echo "Claim Details: " . $this->claimModel->claim_details . "<br>";
        echo "Claim Date: " . $this->claimModel->claim_date . "<br>";

        if ($this->claimModel->create($user_id, $product, $claim_detail, $claim_date)) {
            echo "Claim has been successfully submitted.";
        } else {
            echo "There was an error submitting your claim.";
        }
    }

    public function listClaims()
    {
        $claims = $this->claimModel->getAllClaims();
        foreach ($claims as $claim) {
            echo "Claim ID: " . $claim['id'] . "<br>";
            echo "Product: " . $claim['product'] . "<br>";
            echo "Claim Details: " . $claim['claim_details'] . "<br>";
            echo "Date: " . $claim['claim_date'] . "<br><hr>";
        }
    }

    public function listClaimsByUser($user_id)
    {
        $claims = $this->claimModel->getClaimsByUser($user_id);
        foreach ($claims as $claim) {
            echo "Claim ID: " . $claim['id'] . "<br>";
            echo "Product: " . $claim['product'] . "<br>";
            echo "Claim Details: " . $claim['claim_details'] . "<br>";
            echo "Date: " . $claim['claim_date'] . "<br><hr>";
        }
    }

    public function updateClaim($claim_id, $status)
    {
        // This method should be updated based on your actual requirements
        // For now, we will leave it as it is.
    }


    public function deleteClaim($claim_id)
    {
        if ($this->claimModel->delete($claim_id)) {
            // Redirect with a success message
            header("Location: ../views/claim_list.php?message=Claim+deleted+successfully");
            exit();
        } else {
            // Redirect with an error message
            header("Location: ../views/claim_list.php?message=Failed+to+delete+claim");
            exit();
        }
    }
}
