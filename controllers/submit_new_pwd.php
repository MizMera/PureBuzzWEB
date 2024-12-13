<?php
//récupèration des les valeurs des champs "token", "password", et "confirm_password" envoyées via la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resetToken = $_POST["token"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password !== $confirmPassword) {
        echo json_encode([
            "success" => false,
            "message" => "Passwords do not match."
        ]);
        exit;
    }

    try {
        // Database connection
        $host = "localhost";
        $dbName = "purebuzz";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Cela permet de valider si le token de réinitialisation existe dans la base de données.
        $stmt = $pdo->prepare("SELECT id, reset_token, reset_token_expiry FROM users WHERE reset_token = :reset_token");
        $stmt->bindParam(':reset_token', $resetToken);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérification de la date d'expiration du token
            $currentDateTime = new DateTime();
            $expiryDateTime = new DateTime($user['reset_token_expiry']);

            if ($currentDateTime > $expiryDateTime) {
                echo json_encode([
                    "success" => false,
                    "message" => "Reset token has expired."
                ]);
                exit;
            }

            // Update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET password = :password, reset_token = NULL, reset_token_expiry = NULL WHERE id = :id");
            $updateStmt->bindParam(':password', $hashedPassword);
            $updateStmt->bindParam(':id', $user['id']);
            $updateStmt->execute();

            echo json_encode([
                "success" => true,
                "message" => "Password reset successfully.",
                "redirect" => "../../view/Front_Office/User/login.html"
            ]);
            exit;
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid reset token."
            ]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
}





else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Reset Password</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Arial', sans-serif;
            }

            body {
                background-color: #ffcc4d; /* Golden yellow background */
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .content-wrapper {
                background-color: #ffe4a1; /* Soft yellow for form container */
                border-radius: 10px;
                padding: 30px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                width: 90%;
                max-width: 400px;
                text-align: center;
            }

            .logo-section img {
                width: 120px;
                margin-bottom: 20px;
            }

            h4 {
                color: #333;
                font-weight: bold;
                margin-bottom: 15px;
            }

            h6 {
                color: #666;
                font-size: 0.9rem;
                margin-bottom: 20px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .form-control {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 1rem;
            }

            .btn-primary {
                background-color: #d18a00; /* Honey color for buttons */
                color: #fff;
                border: none;
                padding: 10px 20px;
                font-size: 1rem;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 10px;
                width: 100%;
            }

            .btn-primary:hover {
                background-color: #b97700; /* Darker honey shade */
            }

            .error-message {
                color: red;
                font-size: 0.9rem;
                display: none;
                margin-top: -10px;
                margin-bottom: 10px;
            }

            .success-message {
                color: green;
                font-size: 0.9rem;
                display: none;
                margin-bottom: 10px;
            }
        </style>
    </head>
    <body>
    <div class="content-wrapper">
        <div class="logo-section">
            <img src="../../../../assets/PureBuzzLogo.png" alt="PureBuzz Logo">
        </div>
        <h4>Reset Your Password</h4>
        <h6>Enter your new password below and confirm it.</h6>
        <form id="resetPasswordForm" method="POST" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="New Password" required>
            </div>
            <div class="form-group">
                <input type="password" id="confirmPassword" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                <div id="error-message" class="error-message"></div>
            </div>
            <button type="submit" class="btn-primary">Reset Password</button>
        </form>
    </div>
    <script>
        document.getElementById('resetPasswordForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMessage = document.getElementById('error-message');

            errorMessage.style.display = 'none';

            if (password !== confirmPassword) {
                errorMessage.textContent = "Passwords do not match.";
                errorMessage.style.display = 'block';
                return;
            }

            // Submit the form
            fetch('', {
                method: 'POST',
                body: new URLSearchParams(new FormData(this))
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '../../view/Front_Office/User/log_in.html'; // Redirect to login page
                    } else {
                        errorMessage.textContent = data.message;
                        errorMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    errorMessage.textContent = "An error occurred. Please try again.";
                    errorMessage.style.display = 'block';
                });
        });
    </script>
    </body>
    </html>
    <?php
}
?>