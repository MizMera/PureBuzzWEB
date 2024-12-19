<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../assets/PHPMailer-master/src/Exception.php';
require '../../assets/PHPMailer-master/src/PHPMailer.php';
require '../../assets/PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    try {
        // Database connection
        $host = "localhost";
        $dbName = "purebuzz_db";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //  prépare et exécute une requête SQL pour vérifier si l'email fourni existe dans la table users
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // token est généré de manière sécurisée avec la fonction random_bytes(32) et converti en une chaîne hexadécimale avec bin2hex.
            $resetToken = bin2hex(random_bytes(32));
            $resetTokenExpiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // 1-hour validity

            // Update the database with the reset token
            $updateStmt = $pdo->prepare("UPDATE users SET reset_token = :reset_token, reset_token_expiry = :expiry WHERE id = :id");
            $updateStmt->bindParam(':reset_token', $resetToken);
            $updateStmt->bindParam(':expiry', $resetTokenExpiry);
            $updateStmt->bindParam(':id', $user['id']);
            $updateStmt->execute();

            // Send reset email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'sandbox.smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Username = '17009d73e75647'; // Mailtrap Username
                $mail->Password = '6f3a18807b967a'; // Mailtrap Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 2525;

                $resetLink = "http://localhost:63342/../controllers/user/submit_new_pwd.php?token=$resetToken";
                $mail->setFrom('noreply@purebuzz.com', 'PureBuzz Team');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Reset Your Password';
                $mail->Body = "
                    <h1>Password Reset Request</h1>
                    <p>Click the link below to reset your password:</p>
                    <a href='$resetLink'>Reset My Password</a>
                    <p>This link will expire in 1 hour.</p>
                ";

                $mail->send();

                echo json_encode([
                    "success" => true,
                    "message" => "A password reset email has been sent to your email address."
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to send reset email. Error: {$mail->ErrorInfo}"
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No account found with that email."
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
}
?>
