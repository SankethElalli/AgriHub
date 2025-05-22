<?php
require_once 'config.php';
require_once '../../sql.php';
session_start();

// Get JSON POST data
$data = json_decode(file_get_contents('php://input'), true);
$orderID = $data['orderID'];

// Verify the payment with PayPal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_API_URL . '/v2/checkout/orders/' . $orderID);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode(PAYPAL_CLIENT_ID . ':' . PAYPAL_APP_SECRET)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $order = json_decode($response, true);
    
    if ($order['status'] === 'COMPLETED') {
        // Store both USD and INR amounts
        $_SESSION['payment_completed'] = true;
        $_SESSION['payment_id'] = $orderID;
        $_SESSION['payment_amount_usd'] = $order['purchase_units'][0]['amount']['value'];
        $_SESSION['payment_amount_inr'] = $_SESSION['amount']; // Original INR amount
        
        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Payment verification failed']);
