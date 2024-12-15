<?php
include_once __DIR__.'/../config.php';
require_once __DIR__.'/../Models/harvests.php';

class HarvestC
{
    function ajouterHarvest($harvest)
    {
        $sql = "INSERT INTO harvests (date, location, quantity, quality, apiary) 
                VALUES (:date, :location, :quantity, :quality, :apiary)";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'date' => $harvest->getDate(),
                'location' => $harvest->getLocation(),
                'quantity' => $harvest->getQuantity(),
                'quality' => $harvest->getQuality(),
                'apiary' => $harvest->getApiary()
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    
    function afficherHarvests()
    {
        $sql = "SELECT harvests.*, apiaries.apiaryName 
                FROM harvests 
                JOIN apiaries ON harvests.apiary = apiaries.idApiary";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
    

    function supprimerHarvest($id)
    {
        $sql = "DELETE FROM harvests WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur:' . $e->getMessage());
        }
    }

    function recupererHarvest($id)
    {
        $sql = "SELECT * FROM harvests WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            $harvest = $query->fetch(PDO::FETCH_ASSOC);
            return $harvest;
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    
    function modifierHarvest($harvest, $id)
    {
        $sql = "UPDATE harvests SET 
                date = :date,
                location = :location,
                quantity = :quantity,
                quality = :quality,
                apiary = :apiary
                WHERE id = :id";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'date' => $harvest->getDate(),
                'location' => $harvest->getLocation(),
                'quantity' => $harvest->getQuantity(),
                'quality' => $harvest->getQuality(),
                'apiary' => $harvest->getApiary(),
                'id' => $id
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    public function countHarvestsWithSearch($search)
    {
        $sql = "SELECT COUNT(*) AS total FROM harvests h
                JOIN apiaries a ON h.apiary = a.idApiary
                WHERE 
                h.date LIKE :search OR 
                h.location LIKE :search OR 
                h.quantity LIKE :search OR 
                h.quality LIKE :search OR 
                a.apiaryName LIKE :search";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([':search' => '%' . $search . '%']);
            return $query->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    
    public function fetchFilteredSortedHarvests($search, $sort, $limit, $offset)
    {
        $sql = "SELECT h.*, a.apiaryName FROM harvests h
                JOIN apiaries a ON h.apiary = a.idApiary
                WHERE 
                h.date LIKE :search OR 
                h.location LIKE :search OR 
                h.quantity LIKE :search OR 
                h.quality LIKE :search OR 
                a.apiaryName LIKE :search
                ORDER BY h.date $sort
                LIMIT :limit OFFSET :offset";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

}
?>