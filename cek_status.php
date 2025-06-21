<?php
require_once dirname(__FILE__) . '/Midtrans/Midtrans.php';

\Midtrans\Config::$isProduction = false;
\Midtrans\Config::$serverKey = 'SB-Mid-server-woojna9hDvzc4ie0OT8x3V-F'; // Ganti dengan server key kamu

header('Content-Type: application/json');

if (isset($_POST['order_id'])) {
    $orderId = $_POST['order_id'];

    try {
        $status = \Midtrans\Transaction::status($orderId);

        // Pastikan hasil dikonversi ke array
        $statusArray = (array) $status;

        echo json_encode([
            'status_code' => $statusArray['status_code'] ?? '',
            'transaction_status' => $statusArray['transaction_status'] ?? '',
            'order_id' => $statusArray['order_id'] ?? '',
            'gross_amount' => $statusArray['gross_amount'] ?? '',
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'error' => 'order_id tidak dikirim'
    ]);
}
