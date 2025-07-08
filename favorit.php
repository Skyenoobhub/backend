<?php
header("Content-Type: application/json");
include 'koneksi.php';

$query = "SELECT f.id, f.trip_id, p.nama_paket, p.harga, p.foto 
          FROM trip_favorit f 
          JOIN paket p ON f.trip_id = p.id 
          ORDER BY f.id DESC";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(["data" => $data]);
?>
