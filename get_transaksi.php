<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$db = "travelapps";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Koneksi gagal']);
    exit;
}

$nama_user = $_GET['nama_user'] ?? '';

if (empty($nama_user)) {
    echo json_encode(['success' => false, 'error' => 'Nama user tidak dikirim']);
    exit;
}

$sql = "SELECT nama_paket, harga, deskripsi, rincian, fasilitas FROM transaksi WHERE nama_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nama_user);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['success' => true, 'data' => $data]);

$stmt->close();
$conn->close();
?>
