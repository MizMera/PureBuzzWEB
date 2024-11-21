<?php
require_once '../Controller/ApiaryController.php'; // Include the controller

$controller = new ApiaryController(); // Create the controller instance


// Switch based on request method
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $controller->getAllApiaries();
        break;
    case 'POST':
        $controller->addApiary();
        break;
    case 'PUT':
        $controller->editApiary();
        break;
    case 'DELETE':
        $controller->deleteApiary();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        break;
}

?>
