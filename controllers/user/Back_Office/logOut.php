<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Send a success response to the client
http_response_code(200);
echo json_encode(["success" => true, "message" => "Logged out successfully."]);
exit();
