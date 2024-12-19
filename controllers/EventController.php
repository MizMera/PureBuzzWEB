<?php

include_once '../models/Event.php';

class EventController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllEvents() {
        $stmt = $this->db->query("SELECT * FROM events");
        $events = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events[] = new Event($row['id'], $row['name'], $row['location'], $row['description'], $row['num_tickets'], $row['img']);
        }
        return $events;
    }

    public function addEvent($name, $location, $description, $numTickets, $image) {
        $stmt = $this->db->prepare("INSERT INTO events (name, location, description, num_tickets, img) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$name, $location, $description, $numTickets, $image]);
    }

    public function updateEvent($id, $name, $location, $description, $numTickets) {
        $stmt = $this->db->prepare("UPDATE events SET name=?, location=?, description=?, num_tickets=? WHERE id=?");
        return $stmt->execute([$name, $location, $description, $numTickets, $id]);
    }

    public function deleteEvent($id) {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function getEventById($id) {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Event($row['id'], $row['name'], $row['location'], $row['description'], $row['num_tickets'], $row['img']);
        }
        return null;
    }
}

