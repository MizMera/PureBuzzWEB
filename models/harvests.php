<?php
class Harvest
{
    private $id;
    private $date;
    private $location;
    private $quantity;
    private $quality;
    private $apiary;

    public function __construct($id, $date, $location, $quantity, $quality, $apiary)
    {
        $this->id = $id;
        $this->date = $date;
        $this->location = $location;
        $this->quantity = $quantity;
        $this->quality = $quality;
        $this->apiary = $apiary;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getQuality()
    {
        return $this->quality;
    }

    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    public function getApiary()
    {
        return $this->apiary;
    }

    public function setApiary($apiary)
    {
        $this->apiary = $apiary;
    }
}
?>