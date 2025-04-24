<?php
require '../db/connection.php';
require_once '../db/auth.php';

header('Content-Type: application/json');
$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

$user = verifyToken($token);
if (!$user) {
    echo json_encode(["status" => false, "message" => "Unauthorized"]);
    exit;
}
// Query semua data dari tabel menu
$category = $_GET['category'] ?? 'minuman';
$query = "SELECT id, nama, namamenu, deskripsi, harga, url_gambar FROM menu WHERE category = '$category'";
$result = $conn->query($query);



// Cek apakah ada data
if ($result && $result->num_rows > 0) {
    $menus = [];

    while ($row = $result->fetch_assoc()) {
        $menus[] = [
            "id" => (int)$row["id"],
            "nama" => $row["nama"],
            "namamenu" => $row["namamenu"],
            "deskripsi" => $row["deskripsi"],
            "harga" => (int)$row["harga"],
            "url_gambar" => $row["url_gambar"]
        ];
    }

    echo json_encode([
        "status" => true,
        "data" => $menus
    ]);
} else {
    echo json_encode([
        "status" => false,
        "message" => "Menu tidak ditemukan"
    ]);
}

$conn->close();
?>
