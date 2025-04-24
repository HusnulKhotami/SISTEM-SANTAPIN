<?php
require '../db/connection.php';
require_once '../db/auth.php';

header('Content-Type: application/json');

// Ambil token dari header Authorization
$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

// Verifikasi token
$user = verifyToken($token);
if (!$user) {
    echo json_encode(["status" => false, "message" => "Unauthorized"]);
    exit;
}

$userId = $user['id'];

// Ambil semua data pesanan untuk user tersebut
$query = "SELECT id, nama, tanggal, jam, ruangan, jumlah_orang, payment_method, cart, created_at 
          FROM pesanan 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$pesanan = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pesanan[] = [
            "id" => (int)$row['id'],
            "nama" => $row['nama'],
            "tanggal" => $row['tanggal'],
            "jam" => $row['jam'],
            "ruangan" => $row['ruangan'],
            "jumlah_orang" => (int)$row['jumlah_orang'],
            "payment_method" => $row['payment_method'],
            "cart" => json_decode($row['cart'], true),
            "created_at" => $row['created_at']
        ];
    }

    echo json_encode([
        "status" => true,
        "data" => $pesanan
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Pesanan tidak ditemukan"
    ]);
}

$conn->close();
?>
