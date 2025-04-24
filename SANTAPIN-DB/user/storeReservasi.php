<?php
require '../db/connection.php';
require_once '../db/auth.php';
header('Content-Type: application/json');

$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

$user = verifyToken($token);
$userId = $user['id'];
if (!$user) {
    echo json_encode(["status" => false, "message" => "Unauthorized"]);
    exit;
}
// Ambil dan decode JSON dari body request
$input = json_decode(file_get_contents('php://input'), true);

// Validasi data
if (!isset($input['nama'], $input['jam'], $input['date'], $input['ruangan'], $input['jumlahOrang'], $input['payment'], $input['cart'])) {
    echo json_encode(["status" => false, "message" => "Data tidak lengkap"]);
    exit;
}

$nama = $conn->real_escape_string($input['nama']);
$jam = $conn->real_escape_string($input['jam']);
$tanggal = $conn->real_escape_string($input['date']);
$ruangan = $conn->real_escape_string($input['ruangan']);
$jumlahOrang = (int)$input['jumlahOrang'];
$payment = $conn->real_escape_string($input['payment']);
$cart = $input['cart'];
$cart_json = $conn->real_escape_string(json_encode($cart)); // encode dan escape cart
$created_at = date('Y-m-d H:i:s');

// Simpan ke tabel pesanan (dengan cart JSON)
$query = "INSERT INTO pesanan (nama, jam, tanggal, ruangan, jumlah_orang, payment_method, cart, created_at,user_id) 
          VALUES ('$nama', '$jam', '$tanggal', '$ruangan', $jumlahOrang, '$payment', '$cart_json', '$created_at',$userId)";

if ($conn->query($query)) {
    $pesanan_id = $conn->insert_id;
    echo json_encode(["status" => true, "message" => "Pesanan berhasil disimpan"]);
} else {
    echo json_encode(["status" => false, "message" => "Gagal menyimpan pesanan: " . $conn->error]);
}

$conn->close();
?>
