<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require '../db/connection.php';
header("Content-Type: application/json");


// Ambil token dari header Authorization
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    echo json_encode(["status" => false, "message" => "Unauthorized"]);
    exit;
}

$token = str_replace('Bearer ', '', $authHeader);

// Cek user berdasarkan token
$stmt = $conn->prepare("SELECT id FROM users WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["status" => false, "message" => "Invalid token"]);
    exit;
}

$stmt->bind_result($userId);
$stmt->fetch();

// Kosongkan token
$update = $conn->prepare("UPDATE users SET token = NULL WHERE id = ?");
$update->bind_param("i", $userId);

if ($update->execute()) {
    echo json_encode(["status" => true, "message" => "Logout successful"]);
} else {
    echo json_encode(["status" => false, "message" => "Logout failed"]);
}

$stmt->close();
$update->close();
$conn->close();
?>
