<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";  // Sesuaikan dengan pengaturan MySQL Anda
$password = "";      // Sesuaikan dengan pengaturan MySQL Anda
$dbname = "travelapps"; // Pastikan ini sesuai dengan nama database Anda

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

$name = $conn->real_escape_string($data['name']);
$email = $conn->real_escape_string($data['email']);
$password = password_hash($data['password'], PASSWORD_BCRYPT); // Hash password
$phone = $conn->real_escape_string($data['phone']);
$gender = $conn->real_escape_string($data['gender']);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, gender) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $password, $phone, $gender);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['message' => 'Registration successful.']);
} else {
    echo json_encode(['message' => 'Registration failed: ' . $stmt->error]);
}

// Close connections
$stmt->close();
$conn->close();
?>