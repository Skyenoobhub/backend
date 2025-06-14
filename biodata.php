<?php
header('Content-Type: application/json');

// Memulai sesi untuk memeriksa apakah pengguna sudah login
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "travelapps";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    http_response_code(500); // Internal Server Error
    exit();
}

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['userId'])) {
    echo json_encode(['success' => false, 'message' => 'User is not logged in.']);
    http_response_code(401); // Unauthorized
    exit();
}

// Ambil userId dari session
$userId = $_SESSION['userId'];

// Persiapkan query untuk mengambil data pengguna berdasarkan userId
$query = "SELECT name, email, phone, gender FROM users WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    // Mengikat parameter userId ke query
    $stmt->bind_param("i", $userId);

    // Eksekusi query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_assoc(); // Ambil hasil sebagai array asosiasi

        if ($data) {
            // Kembalikan data pengguna dalam format JSON
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
            http_response_code(200); // OK
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            http_response_code(404); // Not Found
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to execute query.']);
        http_response_code(500); // Internal Server Error
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement.']);
    http_response_code(500); // Internal Server Error
}

// Tutup koneksi ke database
$conn->close();
?>
