<?php
include 'connectDb.php'; // Include database connection

$message = '';

// Auto-fill product details from URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$product_name = '';
$amount = 0;

if ($product_id) {
    $productQuery = "SELECT product_name, price FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($productQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $productResult = $stmt->get_result();

    if ($productData = $productResult->fetch_assoc()) {
        $product_name = $productData['product_name'];
        $amount = $productData['price'];
    }
}

if (isset($_POST['submit'])) {
    $phone = trim($_POST['phone']);
    $mpesa_code = strtoupper(trim($_POST['mpesa_code']));
    $quantity = intval($_POST['quantity']);
    $amount = $quantity * $amount; // Calculate total amount based on quantity
    $product_name = trim($_POST['product_name']);

    // Improved phone validation: Ensures it starts with '07' and has exactly 10 digits
    if (!preg_match('/^07[0-9]{8}$/', $phone)) {
        $message = "Swal.fire('Error', 'Invalid phone number. Must start with 07 and have 10 digits.', 'error');";
    } elseif (!preg_match('/^[A-Z0-9]{10}$/', $mpesa_code)) {
        $message = "Swal.fire('Error', 'Invalid M-Pesa transaction code. Must be 10 characters.', 'error');";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $message = "Swal.fire('Error', 'Please enter a valid amount.', 'error');";
    } else {
        // Fetch product_id from the products table based on product name
        $productQuery = "SELECT product_id, vendor_id FROM products WHERE product_name = ?";
        $stmt = $conn->prepare($productQuery);
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        $productResult = $stmt->get_result();

        if ($productResult->num_rows === 0) {
            $message = "Swal.fire('Error', 'Product not found.', 'error');";
        } else {
            $productData = $productResult->fetch_assoc();
            $product_id = $productData['product_id'];
            $vendor_id = $productData['vendor_id'];

            // Check if transaction code is unique
            $checkQuery = "SELECT * FROM payments WHERE transaction_code = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $mpesa_code);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $message = "Swal.fire('Error', 'This M-Pesa transaction code has already been used.', 'error');";
            } else {
                // Insert payment record
                $insertQuery = "INSERT INTO payments (phone, amount, transaction_code, status) VALUES (?, ?, ?, 'Pending')";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("sss", $phone, $amount, $mpesa_code);

                if ($stmt->execute()) {
                    // Insert sale record
                    $saleQuery = "INSERT INTO sales (product_id, vendor_id, quantity, sale_date) VALUES (?, ?, ?, NOW())";
                    $stmt = $conn->prepare($saleQuery);
                    $stmt->bind_param("iii", $product_id, $vendor_id, $quantity);
                    $stmt->execute();

                    $message = "Swal.fire('Success', 'Payment submitted successfully! Your order is being processed.', 'success').then(() => window.location.href='market.php');";
                } else {
                    $message = "Swal.fire('Error', 'Payment not recorded. Please try again.', 'error');";
                }
            }
        }
    }
}



// new code

function generateBasicAuthToken() {
    $credentials = 'GyVW6qg8VgDWw6ZmfxwS' . ':' . "HVcpupKRvvKNpiqmswiNea3mnrWxkUwdM8ikyfhn";
    $encodedCredentials = base64_encode($credentials);
    return 'Basic ' . $encodedCredentials;
}


function initiatePayment_PayHero($amount, $phone_number, $channel_id, $external_reference) {
    // return false;
    $basicAuthToken = generateBasicAuthToken();

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://backend.payhero.co.ke/api/v2/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode([
            "amount" => floatval($amount), // Ensure amount is a numeric value
            "phone_number" => $phone_number,
            "channel_id" => $channel_id,
            "provider" => "m-pesa",
            "external_reference" => $external_reference,
            "callback_url" => "path to your call back"
        ]),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: ' . $basicAuthToken
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        error_log("cURL Error: $error");
        return ['success' => false, 'message' => "cURL Error: $error"];
    }

    error_log("HTTP Code: $httpCode");
    error_log("Payhero API Response: " . $response);
    // echo $response;
    $decodedResponse = json_decode($response, true);

     // Check if the response has an error code or was unsuccessful
     if (isset($decodedResponse['error_code'])) {
        error_log("API Error: " . $decodedResponse['error_message']);
        return false;
    }
    if (isset($decodedResponse['success']) && $decodedResponse['success'] === true) {
        return true;
    }
    return false;
}

$messo_auto="";

if(isset($_POST['submit_auto'])){
    $phone_auto=trim($_POST['phone_auto']);
    $amount_auto=floor(trim($_POST['amount_auto']));

    if(!empty($phone_auto)){
        // now initiate

        if(initiatePayment_PayHero($amount_auto, $phone_auto, 1881, "my reference here")){
            $messo_auto=" <div class='alert alert-success  alert-dismissible fade show' style='font-size: small;'>
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                    <strong><i class='fas fa-circle-check'></i> Mpesa popup sent!</strong> kindly check your phone and verify payment
                </div>";
        }else{
             $messo_auto="<div class='alert alert-danger  alert-dismissible fade show' style='font-size: small;'>
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                <strong><i class='fas fa-circle-xmark'></i> Oops! failed to initiate mpesa popup</strong> kindly try again later
            </div>";
        }


    }
}



// end new code
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Payment</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function calculateTotal() {
            const price = <?php echo $amount; ?>;
            const quantity = document.querySelector('input[name="quantity"]').value;
            const totalAmount = price * quantity;
            document.querySelector('input[name="amount"]').value = totalAmount;
        }
    </script>
</head>
<body>
<br>
<center> <h1>Complete your Payment</h1>

<div class="mt-3 mb-3 w-75">
    <?php echo $messo_auto;?>

</div>

</center>
<div style="display: flex; flex-direction:column;">
<!-- new code -->
 <div class="container border border-2 rounded p-2">
 <h2>Automatic Payment</h2>
    <p>Please enter your Mpesa number, you will receive a popup, enter mpesa pin to confirm payment</p>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="text" name="phone_auto" class="form-control" placeholder="07XXXXXXXX" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount:</label>
                <input type="number" name="amount_auto" class="form-control" value="<?php echo htmlspecialchars($amount); ?>" readonly>
            </div>
            <button type="submit" name='submit_auto' class="btn btn-primary">Submit</button>
        </form>



 </div>


<!-- end new code -->















    <div class="container mt-5 mb-3 border border-2 rounded p-2">
        <h2>Manual Payment</h2>
        <p>Please send payment to <strong>Till Number: 123456</strong> and enter the transaction code below.</p>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="text" name="phone" class="form-control" placeholder="07XXXXXXXX" required>
            </div>
            <div class="mb-3">
                <label for="product_name" class="form-label">Product Name:</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product_name); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" class="form-control" placeholder="Enter quantity" min="1" required oninput="calculateTotal()">
            </div>
            <div class="mb-3">
                <label for="mpesa_code" class="form-label">M-Pesa Transaction Code:</label>
                <input type="text" name="mpesa_code" class="form-control" placeholder="ABC123DEF4" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Amount:</label>
                <input type="number" name="amount" class="form-control" value="<?php echo htmlspecialchars($amount); ?>" readonly>
            </div>
            <button type="submit" class="btn btn-primary" name='submit'>Submit Payment</button>
        </form>
    </div>

    </div>

    <!-- Display message if set -->
    <?php if ($message): ?>
        <script>
            <?php echo $message; ?>
        </script>
    <?php endif; ?>
</body>
</html>
