<?php

class Database
{
    private static $pdo = null;

    private static function connectDatabase()
    {
        $host = 'localhost';    
        $dbname = 'purebuzz_db';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            self::$pdo = self::connectDatabase();
            echo 'Connected successfully <br>';
        }
        return self::$pdo;
    }
}

// Usage
Database::getConnexion();
