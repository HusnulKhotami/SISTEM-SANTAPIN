<?php
require_once '../db/connection.php';

function verifyToken($token) {
    global $conn;

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc(); // Return user data
    } else {
        return false;
    }
}
