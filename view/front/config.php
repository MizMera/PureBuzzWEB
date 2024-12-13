<?php

class Config
{
    private static $pdo = null;
    public static function getConnexion()
    {
        // If the PDO instance is not yet created, create it
        if (!isset(self::$pdo)) {
            
                // Database connection settings
                $host = 'localhost';        // Host name
                $dbname = 'panier1';        // Database name
                $username = 'root';         // Database username
                $password = '';             // Database password (empty for localhost by default)
                try {
                // Create a new PDO instance
                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (Exception $e) {
                // Display an error message if the connection fails
                die('Erreur: ' . $e->getMessage());
            }
        }
        
        // Return the PDO instance for further use
        return self::$pdo;
    }
}

?>
