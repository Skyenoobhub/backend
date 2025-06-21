<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "travelapps";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$email = isset($data['email']) ? trim($data['email']) : '';
$password = isset($data['password']) ? trim($data['password']) : '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit();
}

error_log("Email received: " . $email);
error_log("Password received: " . $password);

$stmt = $conn->prepare('SELECT * FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        echo json_encode(['success' => true, 'message' => 'Login successful.', 'user' => $user]);
    } else {
        error_log("Password verification failed for email: $email"); // Log the failure
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} else {
    error_log("No user found with email: $email"); // Log no user found
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}

$stmt->close();
$conn->close();
?>