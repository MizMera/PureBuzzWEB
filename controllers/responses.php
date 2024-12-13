<?php

require_once '../models/responses.php';
require_once '../models/claims.php';
 // Corrected the path if necessary
require_once __DIR__ . '/../libs/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer-master/src/SMTP.php';
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
            $this->sendEmailResponse($claimId,$responseDetails);
            echo json_encode(["message" => "Response submitted successfully! an email is sent to the user"]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e]);
        }
    }
    private function sendEmailResponse($claimId, $responseDetails)
    {
        require '../vendor/autoload.php'; // Load Composer autoloader if necessary
    
        // Retrieve claim details and user email using the claim ID
        $claimDetails = $this->responseModel->getResponsesByClaimId(); // Replace with actual model method
        if (!$claimDetails) {
            error_log("Claim details not found for claim ID: $claimId");
            return;
        }
    
        $userEmail ="omar.hamdi204@gmail.com"; // Assuming 'user_email' exists in the claim details
        $product = $claimDetails['product_name']; // Assuming 'product_name' exists in the claim details
    
        $mail = new PHPMailer\PHPMailer\PHPMailer();
    
        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'djangomailer040@gmail.com'; // Your email address
            $mail->Password = 'cdgh rufa gtwr ocqe'; // Your email password or app-specific password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            // Email headers and content
            $mail->setFrom('djangomailer040@gmail.com', 'PureBuzz');
            $mail->addAddress($userEmail); // Recipient email
    
            $mail->isHTML(true);
            $mail->Subject = 'Response to Your Claim';
            $mail->Body = "
                <h1>Response to Your Claim</h1>
                <p>We have reviewed your claim for the product: <strong>{$product}</strong>.</p>
                <p><strong>Response Details:</strong> {$responseDetails}</p>
                <br>
                <p>Regards,</p>
                <p>PureBuzz</p>
            ";
    
            // Send the email
            $mail->send();
            error_log("Email sent successfully to: $userEmail");
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
    
    public function list_response()
    {
        $ResponseModel = new ResponseModel();
        try {
            $response = $ResponseModel->getResponsesByClaimId();
            echo json_encode(["responses" => $response]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to retrieve claims. " . $e->getMessage()]);
        }
    }
    public function update_response()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid JSON input."]);
            return;
        }

        // Extract Response_ID and Response_Detail from the input data
        $responseID = ltrim($data['Response_ID'], '#'); // Remove '#' if present
        $responseDetail = $data['Response_Detail'] ?? null;

        if (!$responseID || !$responseDetail) {
            http_response_code(400);
            echo json_encode(["error" => "All fields are required. Response ID or Response Detail is missing."]);
            return;
        }

        $responseModel = new ResponseModel();

        try {
            // Call the model's method to update the response
            if ($responseModel->updateResponse($responseID, $responseDetail)) {
                echo json_encode(["message" => "Response updated successfully."]);
            } else {
                // Handle the case where no rows were affected
                http_response_code(404);
                echo json_encode(["error" => "Response not found or no changes made."]);
            }
        } catch (Exception $e) {
            // Log the error message for debugging
            error_log("Error in update_response: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["error" => "Failed to update response. " . $e->getMessage()]);
        }
    }
    public function deleteResponse()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        // Check if the data is valid and contains the response_id
        if (!$data || !isset($data['response_id'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request. Response ID is required."]);
            return;
        }

        $responseID = $data['response_id'];

        $responseModel = new ResponseModel();

        try {
            // Call the delete method on the ResponseModel
            if ($responseModel->deleteResponse($responseID)) {
                echo json_encode(["message" => "Response deleted successfully."]);
            } else {
                http_response_code(404);
                echo json_encode(["error" => "Response not found."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to delete response. " . $e->getMessage()]);
        }
    }
}
