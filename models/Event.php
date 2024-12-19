<?php

class Event {
    private $id;
    private $name;
    private $location;
    private $description;
    private $numTickets;
    private $image;

    public function __construct($id, $name, $location, $description, $numTickets, $image) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
        $this->description = $description;
        $this->numTickets = $numTickets;
        $this->image = $image;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getLocation() { return $this->location; }
    public function getDescription() { return $this->description; }
    public function getNumTickets() { return $this->numTickets; }
    public function getImage() { return $this->image; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setLocation($location) { $this->location = $location; }
    public function setDescription($description) { $this->description = $description; }
    public function setNumTickets($numTickets) { $this->numTickets = $numTickets; }
    public function setImage($image) { $this->image = $image; }
}

