<?php
// Aktifkan log error
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include Midtrans
require_once dirname(__FILE__) . '/Midtrans/Midtrans.php';

// Konfigurasi Midtrans
\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$serverKey = 'SB-Mid-server-woojna9hDvzc4ie0OT8x3V-F';
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Header JSON
header('Content-Type: application/json');

// Log semua input ke file untuk debug
file_put_contents(__DIR__ . '/log_token.txt', print_r($_POST, true));

// Ambil dan validasi data
$nama_paket = $_POST['nama_paket'] ?? null;
$harga = isset($_POST['harga']) ? (int)$_POST['harga'] : null;
$deskripsi = $_POST['deskripsi'] ?? '';
$rincian = $_POST['rincian'] ?? '';
$fasilitas = $_POST['fasilitas'] ?? '';
$nama_user = $_POST['nama_user'] ?? 'User';

if ($nama_paket && $harga) {
    $orderId = 'TRX-' . rand(100000, 999999);

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $harga
        ],
        'item_details' => [[
            'id' => 'paket_' . $orderId,
            'price' => $harga,
            'quantity' => 1,
            'name' => $nama_paket
        ]],
        'customer_details' => [
            'first_name' => $nama_user,
            'email' => 'edward@gmail.com',
            'phone' => '081234567890'
        ]
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        echo json_encode([
            'token' => $snapToken,
            'order_id' => $orderId
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'error' => 'Midtrans error: ' . $e->getMessage()
        ]);
    }

} else {
    echo json_encode([
        'error' => 'Data tidak lengkap: pastikan nama_paket dan harga dikirim.'
    ]);
}
