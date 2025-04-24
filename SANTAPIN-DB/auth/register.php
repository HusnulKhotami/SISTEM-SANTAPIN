<?php
require '../db/connection.php';
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$role = $data['role'] ?? 'user';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => false, "message" => "Username and password required"]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashedPassword, $role);

if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "User registered successfully"]);
} else {
    echo json_encode(["status" => false, "message" => "Registration failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
