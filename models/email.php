<?php
// Ensure you have included the PHPMailer classes
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailModel {
    private $mail;

    public function __construct() {
        // Create a new PHPMailer instance
        $this->mail = new PHPMailer(true);
        
        // Server settings
        $this->mail->isSMTP(); // Set mailer to use SMTP
        $this->mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $this->mail->SMTPAuth = true; // Enable SMTP authentication
        $this->mail->Username = 'djangomailer040@gmail.com'; // Your Gmail address
        $this->mail->Password = 'cdgh rufa gtwr ocqe'; // Your Gmail password or app password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $this->mail->Port = 587; // TCP port to connect to
    }

    public function sendEmail($to, $subject, $message) {
        try {
            // Recipients
            $this->mail->setFrom('djangomailer040@gmail.com');
            $this->mail->addAddress($to); // Add a recipient

            // Content
            $this->mail->isHTML(false); // Set email format to plain text
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;

            // Send the email
            $this->mail->send();
            return true; // Email sent successfully
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return false; // Email failed to send
        }
    }
}
?>