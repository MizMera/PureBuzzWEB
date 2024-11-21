<?php
require_once '../Controller/HarvestController.php';

$controller = new HarvestController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $controller->getAllHarvests();
        break;
    case 'POST':
        $controller->addHarvest();
        break;
    case 'PUT':
        $controller->editHarvest();
        break;
    case 'DELETE':
        $controller->deleteHarvest();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        break;
}
?>
