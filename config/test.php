<?php

include __DIR__. '/../config/database.php';

try {
    $db = Database::getConnexion();
    echo "Database connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
