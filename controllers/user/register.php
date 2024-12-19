<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../assets/PHPMailer-master/src/Exception.php';
require '../../assets/PHPMailer-master/src/PHPMailer.php';
require '../../assets/PHPMailer-master/src/SMTP.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $dateOfBirth = $_POST["date_of_birth"];
    $location = $_POST["location"];
    $mobile = $_POST["mobile"];
    $gender = $_POST["gender"];
    $role = $_POST["role"];
    $profilePicture = $_FILES["profile_picture"]["name"];

    $verificationToken = bin2hex(random_bytes(32)); // Generation dun  token unique

    try {
        // Database connection
        $host = "localhost";
        $dbName = "purebuzz_db";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ensure upload directory exists
        $uploadDir = '../../assets/images/uploads/profile_pictures/';
        // Vérifie si le chemin $uploadDir est un répertoire existant.
        //les fichiers peuvent y être téléchargés
        if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
            echo json_encode([
                "success" => false,
                "message" => "Upload directory does not exist or is not writable."
            ]);
            exit;
        }

        // vérifie si le téléchargement du fichier a échoué
        if ($_FILES['profile_picture']['error'] !== UPLOAD_ERR_OK) { //contient le code d'erreur associé au téléchargement du fichier.
            echo json_encode([
                "success" => false,
                "message" => "Error uploading profile picture: " . $_FILES['profile_picture']['error']
            ]);
            exit;
        }

        // le fichier sera stocké après son téléchargement
        //basename($profilePicture) extrait le nom du fichier
        $uploadFilePath = $uploadDir . basename($profilePicture);
        //Cette ligne tente de déplacer le fichier temporaire
        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFilePath)) {
            echo json_encode([
                "success" => false,
                "message" => "Failed to upload profile picture."
            ]);
            exit;
        }

        // Insert user into the database
        $stmt = $pdo->prepare(
            "INSERT INTO users (first_name, last_name, email, password, date_of_birth, location, mobile, gender, role, profile_picture, verification_token)
             VALUES (:first_name, :last_name, :email, :password, :date_of_birth, :location, :mobile, :gender, :role, :profile_picture, :verification_token)"
        );

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':date_of_birth', $dateOfBirth);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':profile_picture', $profilePicture);
        $stmt->bindParam(':verification_token', $verificationToken);

        $stmt->execute();

        // Send verification email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '17009d73e75647'; // Mailtrap Username
            $mail->Password = '6f3a18807b967a'; // Mailtrap Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;

            $verificationLink = "http://localhost:63342/PureBuzzWEB-integration/controllers/user/verify.php?token=$verificationToken";
            $mail->setFrom('noreply@purebuzz.com', 'PureBuzz Team');
            $mail->addAddress($email, "$firstName $lastName");
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email Address';
            $mail->Body = "
                <h1>Welcome, $firstName!</h1>
                <p>Please verify your email by clicking below:</p>
                <a href='$verificationLink'>Verify My Email</a>
            ";

            $mail->send();

            echo json_encode([
                "success" => true,
                "message" => "User registered successfully! A verification email has been sent."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "User registered, but email could not be sent. Error: {$mail->ErrorInfo}"
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
