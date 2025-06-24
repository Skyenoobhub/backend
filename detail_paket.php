<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *'); // Allow CORS
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "travelapps"; 

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Ambil id paket dari query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan detail paket berdasarkan id
$sql = "SELECT id, nama_paket, harga, deskripsi, fasilitas, foto, rincian FROM paket WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah ada data
if ($result->num_rows > 0) {
    $data_paket = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $data_paket]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Package not found']);
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>