<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Credentials: true");

$host = "localhost";
$user = "root";
$pass = "";
$db = "santapin";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "status" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}
?>
