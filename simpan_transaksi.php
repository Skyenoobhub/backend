<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db = "travelapps";
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Koneksi gagal: " . $conn->connect_error]));
}

// Ambil input JSON
$data = json_decode(file_get_contents("php://input"), true);

// Simpan ke log
file_put_contents("log_trx.txt", print_r($data, true));

// Ambil nilai
$nama_paket  = $data['nama_paket'] ?? '';
$harga       = $data['harga'] ?? 0;
$deskripsi   = $data['deskripsi'] ?? '';
$rincian     = $data['rincian'] ?? '';
$fasilitas   = $data['fasilitas'] ?? '';
$nama_user   = $data['nama_user'] ?? '';

// Validasi sederhana
if (empty($nama_paket) || empty($harga) || empty($deskripsi) || empty($rincian) || empty($fasilitas) || empty($nama_user)) {
    echo json_encode(["success" => false, "error" => "Data tidak lengkap"]);
    exit;
}

// Siapkan SQL dan eksekusi
$stmt = $conn->prepare("INSERT INTO detail_transaksi (nama_paket, harga, deskripsi, rincian, fasilitas, nama_user) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdssss", $nama_paket, $harga, $deskripsi, $rincian, $fasilitas, $nama_user);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
