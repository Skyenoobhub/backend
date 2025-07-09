<?php
header("Content-Type: application/json");
include 'koneksi.php';

$query = "SELECT h.id, h.trip_id, p.nama_paket, p.harga, p.foto 
          FROM trip_history h 
          JOIN paket p ON h.trip_id = p.id 
          ORDER BY h.id DESC";

$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(["data" => $data]);
