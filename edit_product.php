<?php
session_start();
include __DIR__ . '/connectDb.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: manage_products.php');
    exit();
}

$product_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header('Location: manage_products.php');
    exit();
}

// Handle product update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if (!empty($name) && is_numeric($price) && is_numeric($stock)) {
        $update_stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, stock = ? WHERE product_id = ?");
        $update_stmt->bind_param("sdii", $name, $price, $stock, $product_id);

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Product updated successfully!";
            header('Location: manage_products.php');
            exit();
        } else {
            $_SESSION['error'] = "Failed to update product.";
        }
        $update_stmt->close();
    } else {
        $_SESSION['error'] = "Invalid product details!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Product</h2>
    <form action="edit_product.php?id=<?php echo $product_id; ?>" method="POST" class="card p-3">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (Ksh)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
        </div>
        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button><br>
        <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
