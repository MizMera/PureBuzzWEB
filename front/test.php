<?php

include __DIR__. 'C:\xampp\htdocs\project1\view\front/config.php';

try {
    $db = Database::getConnexion();
    echo "Database connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>