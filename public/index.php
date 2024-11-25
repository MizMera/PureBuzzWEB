<?php

// Enable CORS
header("Access-Control-Allow-Origin: *"); // Allow all domains, or specify a domain instead of "*"
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); // Allow specific headers
header("Access-Control-Allow-Credentials: true"); // Allow cookies to be sent with requests (optional)

// Handle preflight requests (for methods like PUT, DELETE)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200); // Respond with a 200 status for preflight requests
    exit;
}

$controllerName = $_GET['controller'] ?? 'ClaimController';
$action = $_GET['action'] ?? 'create';

try {
    $controllerFile = "../controllers/{$controllerName}.php";
    if (!file_exists($controllerFile)) {
        throw new Exception("Controller '{$controllerName}' not found.");
    }

    require_once $controllerFile;
    if (!class_exists($controllerName)) {
        throw new Exception("Class '{$controllerName}' not found in '{$controllerFile}'.");
    }

    $controller = new $controllerName();

    if (!method_exists($controller, $action)) {
        throw new Exception("Action '{$action}' not found in '{$controllerName}'.");
    }

    $controller->$action();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
