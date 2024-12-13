<?php
include_once __DIR__.'/../config.php';
require_once __DIR__.'/../models/apiaries.php';

class ApiaryC
{
    function ajouterApiary($apiary)
    {
        $sql = "INSERT INTO apiaries 
                (apiaryName, beekeeper, location, coordinates, date, weather, hiveCount, observation) 
                VALUES 
                (:apiaryName, :beekeeper, :location, :coordinates, :date, :weather, :hiveCount, :observation)";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'apiaryName' => $apiary->getApiaryName(),
                'beekeeper' => $apiary->getBeekeeper(),
                'location' => $apiary->getLocation(),
                'coordinates' => $apiary->getCoordinates(),
                'date' => $apiary->getDate(),
                'weather' => $apiary->getWeather(),
                'hiveCount' => $apiary->getHiveCount(),
                'observation' => $apiary->getObservation()
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    
    function afficherApiaries()
    {
        $sql = "SELECT * FROM apiaries";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    function supprimerApiary($id)
    {
        $sql = "DELETE FROM apiaries WHERE idApiary = :idApiary";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':idApiary', $id);
        try {
            $req->execute();
        } catch (Exception $e) {
            die('Erreur:' . $e->getMessage());
        }
    }

    function recupererApiary($id)
    {
        $sql = "SELECT * FROM apiaries WHERE idApiary = :idApiary";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['idApiary' => $id]);
            $apiary = $query->fetch(PDO::FETCH_ASSOC);
            return $apiary;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
    
    function modifierApiary($apiary, $id)
    {
        $sql = "UPDATE apiaries SET 
                apiaryName = :apiaryName,
                beekeeper = :beekeeper,
                location = :location,
                coordinates = :coordinates,
                date = :date,
                weather = :weather,
                hiveCount = :hiveCount,
                observation = :observation
                WHERE idApiary = :idApiary";
    
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'apiaryName' => $apiary->getApiaryName(),
                'beekeeper' => $apiary->getBeekeeper(),
                'location' => $apiary->getLocation(),
                'coordinates' => $apiary->getCoordinates(),
                'date' => $apiary->getDate(),
                'weather' => $apiary->getWeather(),
                'hiveCount' => $apiary->getHiveCount(),
                'observation' => $apiary->getObservation(),
                'idApiary' => $id
            ]);
        } catch (Exception $e) {
            echo 'Erreur: ' . $e->getMessage();
        }
    }
    //donner le nombre de apiaries dans la base de donnee 
    public function countApiariesWithSearch($search)
    {
        $sql = "SELECT COUNT(*) AS total FROM apiaries WHERE 
                apiaryName LIKE :search OR 
                beekeeper LIKE :search OR 
                location LIKE :search OR 
                coordinates LIKE :search OR 
                date LIKE :search OR 
                weather LIKE :search OR 
                observation LIKE :search";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([':search' => '%' . $search . '%']);
            return $query->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    //recherche 
    public function fetchFilteredSortedApiaries($search, $sort, $limit, $offset)
    {
        $sql = "SELECT * FROM apiaries WHERE 
                apiaryName LIKE :search OR 
                beekeeper LIKE :search OR 
                location LIKE :search OR 
                coordinates LIKE :search OR 
                date LIKE :search OR 
                weather LIKE :search OR 
                observation LIKE :search
                ORDER BY date $sort
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
// pagination 
    public function countApiariesWithSearch1($beekeeper,$search)
    {
        $sql = "SELECT COUNT(*) AS total FROM apiaries WHERE 
                (apiaryName LIKE :search OR 
                beekeeper LIKE :search OR 
                location LIKE :search OR 
                coordinates LIKE :search OR 
                date LIKE :search OR 
                weather LIKE :search OR 
                observation LIKE :search) AND
                beekeeper LIKE :beek";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $query->bindValue(':beek', $beekeeper, PDO::PARAM_STR);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    //tri et filtrage
    public function fetchFilteredSortedApiaries1($beekeeper,$search, $sort, $limit, $offset)
    {
        $sql = "SELECT * FROM apiaries WHERE 
                (apiaryName LIKE :search OR 
                beekeeper LIKE :search OR 
                location LIKE :search OR 
                coordinates LIKE :search OR 
                date LIKE :search OR 
                weather LIKE :search OR 
                observation LIKE :search) AND
                beekeeper LIKE :beek
                ORDER BY date $sort
                LIMIT :limit OFFSET :offset";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $query->bindValue(':limit', $limit, PDO::PARAM_INT);
            $query->bindValue(':offset', $offset, PDO::PARAM_INT);
            $query->bindValue(':beek', $beekeeper, PDO::PARAM_STR);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
    

}
?>