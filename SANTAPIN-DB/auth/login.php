<?php
require '../db/connection.php';
header('Content-Type: application/json');

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => false, "message" => "Username and password required"]);
    exit;
}

// Check user
$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["status" => false, "message" => "User not found"]);
    exit;
}

$stmt->bind_result($id, $hashedPassword, $role);
$stmt->fetch();

if (password_verify($password, $hashedPassword)) {
    $token = bin2hex(random_bytes(16));
    $update = $conn->prepare("UPDATE users SET token = ? WHERE id = ?");
    $update->bind_param("si", $token, $id);
    $update->execute();

    echo json_encode([
        "status" => true,
        "message" => "Login successful",
        "token" => $token,
        "user" => [
            "id" => $id,
            "username" => $username,
            "role" => $role
        ]
    ]);
} else {
    echo json_encode(["status" => false, "message" => "Invalid password"]);
}

$stmt->close();
$conn->close();
?>
