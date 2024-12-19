<?php

class Ticket {
    private $id;
    private $eventId;
    private $username;
    private $phone;
    private $email;
    private $numTickets;

    public function __construct($id, $eventId, $username, $phone, $email, $numTickets) {
        $this->id = $id;
        $this->eventId = $eventId;
        $this->username = $username;
        $this->phone = $phone;
        $this->email = $email;
        $this->numTickets = $numTickets;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getEventId() { return $this->eventId; }
    public function getUsername() { return $this->username; }
    public function getPhone() { return $this->phone; }
    public function getEmail() { return $this->email; }
    public function getNumTickets() { return $this->numTickets; }

    // Setters
    public function setUsername($username) { $this->username = $username; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setEmail($email) { $this->email = $email; }
    public function setNumTickets($numTickets) { $this->numTickets = $numTickets; }
}

