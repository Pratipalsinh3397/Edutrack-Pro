<?php
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = 'rzp_test_URusGU7I9JN59g';
$api_secret = 'HaCBIlbIdWpDtzIFbYzT9W1F';

$data = json_decode(file_get_contents("php://input"), true);
$amount = intval($data['amount']) * 100; // Convert amount to paise

if ($amount <= 0) {
    echo json_encode(["error" => "Invalid amount"]);
    exit;
}

$api = new Api($api_key, $api_secret);
$order = $api->order->create([
    'amount' => $amount,
    'currency' => 'INR',
    'receipt' => uniqid()
]);

echo json_encode(["order_id" => $order->id]);
?>
