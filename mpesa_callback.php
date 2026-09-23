<?php
include 'connectDb.php'; // Include your database connection

// Log the raw incoming data (for debugging)
$raw_input = file_get_contents('php://input');
file_put_contents('mpesa_callback.log', "[" . date('Y-m-d H:i:s') . "] RAW: " . $raw_input . "\n", FILE_APPEND);

// Decode the JSON payload
$payload = json_decode($raw_input, true);

// Log the decoded payload
file_put_contents('mpesa_callback.log', "[" . date('Y-m-d H:i:s') . "] DECODED: " . print_r($payload, true) . "\n", FILE_APPEND);

if ($payload && isset($payload['status'])) {
    // Extract relevant data from payload
    $status = $payload['status'];
    $transaction_code = $payload['transaction_code'] ?? null;
    $amount = $payload['amount'] ?? null;
    $phone = $payload['phone_number'] ?? null;
    $external_reference = $payload['external_reference'] ?? null;

    // Update database if payment was successful
    if ($status === 'success' && $transaction_code) {
        try {
            $updateQuery = "UPDATE payments SET status = 'Completed' WHERE transaction_code = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("s", $transaction_code);
            $stmt->execute();
            
            file_put_contents('mpesa_callback.log', "[" . date('Y-m-d H:i:s') . "] UPDATED: Transaction $transaction_code\n", FILE_APPEND);
        } catch (Exception $e) {
            file_put_contents('mpesa_callback.log', "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}

// Always return a success response to PayHero
header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);