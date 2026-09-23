<?php
include __DIR__ . '/connectDb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $phone = $_POST['phone'];
    $mpesa_name = $_POST['mpesa_name'];

    // Fetch product details
    $query = "SELECT price, stock FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product || $product['stock'] <= 0) {
        echo "<script>alert('Product out of stock!'); window.location.href='market.php';</script>";
        exit;
    }

    $amount = $product['price'];

    // TODO: Implement M-Pesa STK Push API Call Here
    // Assume M-Pesa payment is successful
    
    $payment_status = true; // Replace with actual response from M-Pesa

    if ($payment_status) {
        // Reduce stock
        $new_stock = $product['stock'] - 1;
        $update_stock = "UPDATE products SET stock = ? WHERE id = ?";
        $stmt = $conn->prepare($update_stock);
        $stmt->bind_param("ii", $new_stock, $product_id);
        $stmt->execute();

        // Insert into sales table
        $insert_sale = "INSERT INTO sales (product_id, vendor_id, quantity) VALUES (?, (SELECT vendor_id FROM products WHERE id = ?), 1)";
        $stmt = $conn->prepare($insert_sale);
        $stmt->bind_param("ii", $product_id, $product_id);
        $stmt->execute();

        echo "<script>alert('Purchase successful!'); window.location.href='market.php';</script>";
    } else {
        echo "<script>alert('Payment failed!'); window.location.href='market.php';</script>";
    }
}
?>
