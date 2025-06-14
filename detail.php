<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *'); // Allow CORS
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";  // Ganti sesuai pengaturan MySQL Anda
$password = "";      // Ganti sesuai pengaturan MySQL Anda
$dbname = "travelapps"; // Pastikan ini sesuai dengan nama database Anda

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Query untuk mendapatkan semua data paket
$sql = "SELECT id, nama_paket, harga, deskripsi, fasilitas, foto, rincian FROM paket";
$result = $conn->query($sql);

// Array untuk menampung data paket
$data_paket = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_paket[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $data_paket]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No packages found']);
}

// Tutup koneksi
$conn->close();
?>