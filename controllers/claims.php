<?php
include_once '../models/claims.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';

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
    
        $userName = $data['user_name'] ?? null; // Email of the user
        $product = $data['product'] ?? null;
        $claimDetails = $data['claim_details'] ?? null;
    
        if (!$userName || !$product || !$claimDetails) {
            http_response_code(400);
            echo json_encode(["error" => "Email, product, and claim details are required."]);
            return;
        }
    
        $claimModel = new ClaimModel();
        try {
            $claimModel->saveClaim($userName, $product, $claimDetails); // Save the claim
    
            // Send email notification
            $this->sendEmailNotification($userName, $product, $claimDetails);
    
            echo json_encode(["message" => "Claim submitted successfully! An email has been sent to you."]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "User or product doesn't exist."]);
        }
    }
    
    /**
     * Sends an email notification to the user.
     */
    private function sendEmailNotification($userEmail, $product, $claimDetails)
    {
        require '../vendor/autoload.php'; // Path to PHPMailer autoload if using Composer
    
        $mail = new PHPMailer\PHPMailer\PHPMailer();
    
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'pure.buzzz@gmail.com'; // Your SMTP username
            $mail->Password = 'salmawalid123'; // Your SMTP password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; // SMTP port (usually 587 for TLS)
    
            // Recipient
            $mail->setFrom('pure.buzzz@gmail.com', 'PureBuzz');
            $mail->addAddress($userEmail); // Recipient email
    
            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Claim Submission Confirmation';
            $mail->Body = "
                <h1>Thank You for Submitting Your Claim</h1>
                <p>We have received your claim for the product: <strong>{$product}</strong>.</p>
                <p><strong>Claim Details:</strong> {$claimDetails}</p>
                <p>Our team will review it and get back to you shortly.</p>
                <br>
                <p>Regards,</p>
                <p>PureBuzz</p>
            ";
    
            // Send email
            $mail->send();
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
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
