<?php
class Apiary
{
    private $idApiary;
    private $apiaryName;
    private $beekeeper;
    private $location;
    private $coordinates;
    private $date;
    private $weather;
    private $hiveCount;
    private $observation;

    public function __construct($idApiary, $apiaryName, $beekeeper, $location, $coordinates, $date, $weather, $hiveCount, $observation) {
        $this->idApiary = $idApiary;
        $this->apiaryName = $apiaryName;
        $this->beekeeper = $beekeeper;
        $this->location = $location;
        $this->coordinates = $coordinates;
        $this->date = $date;
        $this->weather = $weather;
        $this->hiveCount = $hiveCount;
        $this->observation = $observation;
    }

    public function getIdApiary() {
        return $this->idApiary;
    }

    public function getApiaryName() {
        return $this->apiaryName;
    }

    public function setApiaryName($apiaryName) {
        $this->apiaryName = $apiaryName;
    }

    public function getBeekeeper() {
        return $this->beekeeper;
    }

    public function setBeekeeper($beekeeper) {
        $this->beekeeper = $beekeeper;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation($location) {
        $this->location = $location;
    }

    public function getCoordinates() {
        return $this->coordinates;
    }

    public function setCoordinates($coordinates) {
        $this->coordinates = $coordinates;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getWeather() {
        return $this->weather;
    }

    public function setWeather($weather) {
        $this->weather = $weather;
    }

    public function getHiveCount() {
        return $this->hiveCount;
    }

    public function setHiveCount($hiveCount) {
        $this->hiveCount = $hiveCount;
    }

    public function getObservation() {
        return $this->observation;
    }

    public function setObservation($observation) {
        $this->observation = $observation;
    }
}
?>