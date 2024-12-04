<?php
require_once 'email.php';

class EmailController {
    private $emailModel;

    public function __construct() {
        $this->emailModel = new EmailModel();
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $to = $_POST['to'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            if ($this->emailModel->sendEmail($to, $subject, $message)) {
                echo "Email sent successfully!";
            } else {
                echo "Failed to send email.";
            }
        }
    }
}
?>