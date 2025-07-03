<?php
$host = "localhost";        
$user = "root";             
$pass = "";                 
$db   = "travelapps";       

// Membuat koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$conn) {
    die(json_encode([
        "error" => "Koneksi ke database gagal: " . mysqli_connect_error()
    ]));
}
?>
