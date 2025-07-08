<?php
header("Content-Type: application/json");
include 'koneksi.php';

$trip_id = $_POST['trip_id'] ?? '';

if (!empty($trip_id)) {
    $stmt = $conn->prepare("INSERT IGNORE INTO trip_favorit (trip_id) VALUES (?)");
    $stmt->bind_param("s", $trip_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "trip_id kosong"]);
}
?>
