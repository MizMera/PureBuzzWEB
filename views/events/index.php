<?php
require_once 'config/database.php';
require_once '../../controllers/EventController.php';
require_once '../../controllers/TicketController.php';

$eventController = new EventController($access);
$ticketController = new TicketController($access);

$action = $_GET['action'] ?? 'showEvents';

switch ($action) {
    case 'showEvents':
        $events = $eventController->getAllEvents();
        require 'app/views/events.php';
        break;

    case 'getTickets':
        $events = $eventController->getAllEvents();
        require 'app/views/join_us.php';
        break;

    case 'processTicket':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = $_POST['event_id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $numTickets = $_POST['num_tickets'];

            if ($ticketController->addTicket($eventId, $name, $phone, $email, $numTickets)) {
                $message = "Ticket(s) added successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to add ticket(s). Please try again.";
                $messageType = "error";
            }
            $events = $eventController->getAllEvents();
            require 'app/views/join_us.php';
        }
        break;

    case 'admin':
        $tickets = $ticketController->getAllTickets();
        require 'app/views/admin.php';
        break;

    case 'addTicket':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            // Assuming event_id is 1 for simplicity. You might want to add this to the form.
            $eventId = 1;
            $numTickets = 1; // Assuming 1 ticket per submission. Adjust as needed.

            if ($ticketController->addTicket($eventId, $name, $phone, $email, $numTickets)) {
                $message = "Ticket added successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to add ticket. Please try again.";
                $messageType = "error";
            }
        }
        $tickets = $ticketController->getAllTickets();
        require 'app/views/admin.php';
        break;

    case 'updateTicket':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['ticket_id'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            // Assuming num_tickets doesn't change. Add to form if it should be editable.
            $numTickets = 1;

            if ($ticketController->updateTicket($id, $name, $phone, $email, $numTickets)) {
                $message = "Ticket updated successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to update ticket. Please try again.";
                $messageType = "error";
            }
        }
        $tickets = $ticketController->getAllTickets();
        require 'app/views/admin.php';
        break;

    case 'deleteTicket':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'])) {
            if ($ticketController->deleteTicket($_POST['ticket_id'])) {
                $message = "Ticket deleted successfully!";
                $messageType = "success";
            } else {
                $message = "Failed to delete ticket. Please try again.";
                $messageType = "error";
            }
        }
        $tickets = $ticketController->getAllTickets();
        require 'app/views/admin.php';
        break;

    case 'findTicket':
        if (isset($_GET['ticket_id'])) {
            $ticket = $ticketController->getTicketById($_GET['ticket_id']);
            if (!$ticket) {
                $message = "Ticket not found.";
                $messageType = "error";
            }
        }
        $tickets = $ticketController->getAllTickets();
        require 'app/views/admin.php';
        break;
    case 'manageEvents':
        require 'app/views/manage_events.php';
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page not found";
        break;
}

