<?php
session_start();

// efface toutes les variables de session
session_unset();
//détruit complètement la session
session_destroy();

// Send a success response to the client
http_response_code(200);
echo json_encode(["success" => true, "message" => "Logged out successfully."]);
exit();
