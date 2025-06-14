<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'travelapps';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $packageId = intval($_POST['package_id']); // Ensure package_id is treated as integer
    $userId = 1; // Assuming user ID is 1 (adjust this as needed)

    // Insert into the 'favorites' table
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, package_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $userId, $packageId); // Bind as integers
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add favorite"]);
    }
    
    $stmt->close();
}

$conn->close();
?>
