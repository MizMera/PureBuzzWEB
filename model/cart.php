<?php
require_once __DIR__ . 'C:\xampp\htdocs\project1/config.php';

class Cart
{
    private $id;
    private $userId;
    private $dateCreation;
    private $total;

    public function __construct($userId = null)
    {
        if ($userId !== null) {
            $this->userId = $userId;
            $this->dateCreation = date('Y-m-d H:i:s'); // Default to current datetime
            $this->total = 0; // Initialize total to 0
        }
    }

    // Getters and Setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
    }

    // Add a product to the cart
    public function ajouterProduit($produitId, $quantite, $prixUnitaire)
    {
        $pdo = Config::getConnexion();
        try {
            $pdo->beginTransaction();

            // Check if the cart already exists for the user
            $sql = "SELECT id FROM Panier WHERE userId = :userId";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':userId' => $this->userId]);
            $panierId = $stmt->fetchColumn();

            if (!$panierId) {
                // Create a new cart if it doesn't exist
                $sql = "INSERT INTO Panier (userId, dateCreation, total) VALUES (:userId, :dateCreation, :total)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':userId' => $this->userId,
                    ':dateCreation' => $this->dateCreation,
                    ':total' => $this->total,
                ]);
                $panierId = $pdo->lastInsertId();
            }

            // Add the product to the cart
            $sql = "INSERT INTO ArticlePanier (panierId, produitId, quantite, prixUnitaire) 
                    VALUES (:panierId, :produitId, :quantite, :prixUnitaire)
                    ON DUPLICATE KEY UPDATE 
                    quantite = quantite + VALUES(quantite)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':panierId' => $panierId,
                ':produitId' => $produitId,
                ':quantite' => $quantite,
                ':prixUnitaire' => $prixUnitaire,
            ]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Update product quantity in the cart
    public function mettreAJourProduit($articleId, $quantite)
    {
        $pdo = Config::getConnexion();
        $sql = "UPDATE ArticlePanier SET quantite = :quantite WHERE id = :articleId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':quantite' => $quantite,
            ':articleId' => $articleId,
        ]);
    }

    // Remove a product from the cart
    public function supprimerProduit($articleId)
    {
        $pdo = Config::getConnexion();
        $sql = "DELETE FROM ArticlePanier WHERE id = :articleId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':articleId' => $articleId]);
    }

    // Calculate the total price of the cart
    public function calculerTotal()
    {
        $pdo = Config::getConnexion();
        $sql = "SELECT SUM(quantite * prixUnitaire) AS total 
                FROM ArticlePanier 
                WHERE panierId = (SELECT id FROM Panier WHERE userId = :userId)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $this->userId]);
        $this->total = $stmt->fetchColumn();

        // Update the total in the Panier table
        $sql = "UPDATE Panier SET total = :total WHERE userId = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':total' => $this->total,
            ':userId' => $this->userId,
        ]);

        return $this->total;
    }

    // Fetch all products in the cart
    public function getProduits()
    {
        $pdo = Config::getConnexion();
        $sql = "SELECT ap.id AS articleId, ap.produitId, ap.quantite, ap.prixUnitaire, 
                       (ap.quantite * ap.prixUnitaire) AS sousTotal
                FROM ArticlePanier ap
                JOIN Panier p ON ap.panierId = p.id
                WHERE p.userId = :userId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':userId' => $this->userId]);
        return $stmt->fetchAll();
    }
}
?>
