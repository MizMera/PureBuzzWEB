<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Database connection
        $host = "localhost";
        $dbName = "purebuzz";
        $username = "root";
        $passwordDB = "";

        $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $passwordDB);//PHP DATA OBJECTS
        //définit l'attribut de gestion des erreurs de PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Find user with the token et récupère le résultat sous forme de tableau
        $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = :token");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Mark email as verified et statut=1
            $updateStmt = $pdo->prepare("UPDATE users SET status = 1, verification_token = NULL WHERE id = :id");
            $updateStmt->bindParam(':id', $user['id']);
            $updateStmt->execute();









            // Styled HTML output
            echo "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Email Verification</title>
                <link rel='stylesheet' href='../../../assets/css/vertical-layout-light/style.css'>
                   <link rel='stylesheet' href='../../../assets/css/vertical-layout-light/style.css'>
                 <link rel='stylesheet' href='../../../assets/css/Front_Office/signUp.css'>

                  <link rel='stylesheet' href='../../../assets/css/Front_Office/verify.css'>

            </head>
            <body class='bg-light p-5 text-center'>
                <main class='container bg-white p-4 rounded shadow-sm'>
                       <form  >
                            <div class='form-step form-step-active' >
            <div class='progress mb-4'>
                <div class='progress-bar4' style='width: 100%'>100%</div>
            </div>
            <div class='form--header-container text-center mb-4'>
                <h1 class='form--header-title font-weight-bold'>Thank You!</h1>
  <p class='form--header-text text-muted'>Your email has been successfully verified.</p>
                            <p>You can now <a href='../../view/Front_Office/User/log_in.html' class='btn btn-primary text-white'>Log In</a>.</p>            </div>
        </div>

</form>
                    
                </main>
            </body>
            </html>
            ";
        } else {
            // Invalid token message
            echo "
                <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Email Verification</title>
                <link rel='stylesheet' href='../../../assets/css/vertical-layout-light/style.css'>
                   <link rel='stylesheet' href='../../../assets/css/vertical-layout-light/style.css'>
                 <link rel='stylesheet' href='../../../assets/css/Front_Office/signUp.css'>

                  <link rel='stylesheet' href='../../../assets/css/Front_Office/verify.css'>

            </head>
            <body class='bg-light p-5 text-center'>
                <main class='container bg-white p-4 rounded shadow-sm'>
                       <form  >
                            <div class='form-step form-step-active' >
            </div>
            <div class='form--header-container text-center mb-4'>
                 <h1 class='form--header-title font-weight-bold'>Invalid Token</h1>
                            <p class='form--header-text text-muted'>The verification link is invalid or has already been used.</p>
                            <p>Please contact support for assistance.</p>
                            </div>

</form>
                    
                </main>
            </body>
            
            ";
        }
    } catch (PDOException $e) {
        echo "<h1>Error</h1><p>Database error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<h1>Invalid Request</h1><p>No verification token provided.</p>";
}
