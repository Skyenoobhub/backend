<?php
header("Content-Type: application/json");
include 'koneksi.php';

$query = "SELECT t.id, t.nama_paket, t.harga, t.foto
          FROM trip_favorit f
          JOIN paket t ON f.trip_id = t.id";

$result = mysqli_query($conn, $query);

$trips = [];
while ($row = mysqli_fetch_assoc($result)) {
    $trips[] = $row;
}

echo json_encode(['data' => $trips]);
?>
