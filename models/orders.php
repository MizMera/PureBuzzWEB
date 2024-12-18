<?php
include_once '../config/database.php';

class RatesModel {
    private $db;

    public function __construct() {
        $this->db = connectDatabase();
    }

    public function saveFeedback($productName, $rating, $comment) {
        $query = "INSERT INTO feedbacks (product_name, rating, cmt)
                  VALUES (
                      ?,
                      ?,
                      ?
                  )";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$productName,  $rating, $comment]);
    }
    public function getFeedback()
    {
        $query = "SELECT id, product_name, rating, cmt FROM feedbacks"; // No WHERE condition
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}