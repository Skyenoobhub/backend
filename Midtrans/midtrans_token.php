<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ambil library Midtrans
require_once 'Midtrans/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-xxxxxx'; // Ganti dengan punyamu
\Midtrans\Config::$isProduction = false;

// Ambil jumlah dari Flutter
$gross_amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 100000;

// Siapkan data transaksi
$transaction_details = array(
    'order_id' => rand(),
    'gross_amount' => $gross_amount,
);

$params = array(
    'transaction_details' => $transaction_details
);

// Dapatkan Snap Token
$snapToken = \Midtrans\Snap::getSnapToken($params);

// Kirim ke Flutter
echo json_encode(array("token" => $snapToken));
