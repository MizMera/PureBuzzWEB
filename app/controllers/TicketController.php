<?php
require_once __DIR__ . '/../models/Ticket.php';

class TicketController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addTicket($eventId, $username, $phone, $email, $numTickets) {
        $stmt = $this->db->prepare("INSERT INTO tickets (event_id, username, phone, email, num_tickets) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$eventId, $username, $phone, $email, $numTickets]);
    }

    public function getTicketsByEvent($eventId) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE event_id = ?");
        $stmt->execute([$eventId]);
        $tickets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = new Ticket($row['id'], $row['event_id'], $row['username'], $row['phone'], $row['email'], $row['num_tickets']);
        }
        return $tickets;
    }

    public function updateTicket($id, $username, $phone, $email, $numTickets) {
        $stmt = $this->db->prepare("UPDATE tickets SET username = ?, phone = ?, email = ?, num_tickets = ? WHERE id = ?");
        return $stmt->execute([$username, $phone, $email, $numTickets, $id]);
    }

    public function deleteTicket($id) {
        $stmt = $this->db->prepare("DELETE FROM tickets WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getTicketById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tickets WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Ticket($row['id'], $row['event_id'], $row['username'], $row['phone'], $row['email'], $row['num_tickets']);
        }
        return null;
    }

    public function getAllTickets() {
        $stmt = $this->db->query("SELECT * FROM tickets");
        $tickets = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tickets[] = new Ticket($row['id'], $row['event_id'], $row['username'], $row['phone'], $row['email'], $row['num_tickets']);
        }
        return $tickets;
    }
}

