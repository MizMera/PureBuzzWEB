<?php

class Stock
{
    private $id;
    private $product_id;
    private $quantity;
    private $date;
    private $remarks;
    private $db;
    private $table = 'stock_adjustments';

    // Constructor for database connection
    public function __construct($db)
    {
        $this->db = $db; // Initialize database connection
    }

    // Getters and Setters for encapsulation
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getProductId() {
        return $this->product_id;
    }

    public function setProductId($product_id) {
        $this->product_id = $product_id;
        return $this;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
        return $this;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
        return $this;
    }

    public function getRemarks() {
        return $this->remarks;
    }

    public function setRemarks($remarks) {
        $this->remarks = $remarks;
        return $this;
    }

    // Retrieve all stock adjustments
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve a single stock adjustment by ID
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->setId($row['id'])
                 ->setProductId($row['product_id'])
                 ->setQuantity($row['quantity'])
                 ->setDate($row['date'])
                 ->setRemarks($row['remarks']);
        }
        return $row;
    }

    // Create a new stock adjustment
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (product_id, quantity, date, remarks) 
                  VALUES (:product_id, :quantity, :date, :remarks)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $this->quantity, PDO::PARAM_INT);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':remarks', $this->remarks);
        return $stmt->execute();
    }

    // Update an existing stock adjustment
    public function update($id)
    {
        $query = "UPDATE " . $this->table . " 
                  SET product_id = :product_id, quantity = :quantity, date = :date, remarks = :remarks 
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $this->product_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $this->quantity, PDO::PARAM_INT);
        $stmt->bindParam(':date', $this->date);
        $stmt->bindParam(':remarks', $this->remarks);
        return $stmt->execute();
    }

    // Delete a stock adjustment
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>
