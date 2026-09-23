<?php
session_start();
include __DIR__ . '/connectDb.php'; // Include database connection

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];

// Fetch vendor data for profile dropdown
$vendorData = null;
$stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$vendorData = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle Product Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_product'])) {
    $product_name = trim($_POST['product_name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $image_url = trim($_POST['image_url']);

    if (!empty($product_name) && is_numeric($price) && is_numeric($stock)) {
        $stmt = $conn->prepare("INSERT INTO products (vendor_id, product_name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdis", $vendor_id, $product_name, $description, $price, $stock, $image_url);
    
        if ($stmt->execute()) {
            $success_message = "Product added successfully!";
        } else {
            $error_message = "Error adding product.";
        }
        $stmt->close();
    }
}    

// Handle Product Deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ? AND vendor_id = ?");
    $stmt->bind_param("ii", $delete_id, $vendor_id);
    if ($stmt->execute()) {
        $success_message = "Product deleted successfully!";
    } else {
        $error_message = "Error deleting product.";
    }
    $stmt->close();
}

// Handle Sales Report Download
if (isset($_GET['download_report'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sales_report.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Product ID', 'Product Name', 'Quantity Sold', 'Total Revenue', 'Sale Date']);

    $stmt = $conn->prepare("SELECT s.product_id, p.product_name, s.quantity, (s.quantity * p.price) as total, s.sale_date 
                            FROM sales s 
                            JOIN products p ON s.product_id = p.product_id 
                            WHERE s.vendor_id = ?");
    $stmt->bind_param("i", $vendor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Fetch Vendor Products
$products = [];
$stmt = $conn->prepare("SELECT * FROM products WHERE vendor_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #f72585;
            --light-bg: #f8f9fa;
            --dark-bg: #212529;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .product-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .product-img {
            height: 180px;
            object-fit: cover;
            background-color: #f5f5f5;
        }
        
        /* Profile dropdown styles */
        .profile-dropdown {
            position: relative;
        }
        
        .profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }
        
        .profile-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 0;
            overflow: hidden;
        }
        
        .dropdown-item {
            padding: 10px 15px;
            transition: background-color 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dropdown-divider {
            margin: 0;
        }
        
        .user-info {
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 2px;
        }
        
        .user-email {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .user-role {
            display: inline-block;
            padding: 2px 8px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px;
            font-size: 0.75rem;
            margin-top: 5px;
        }
        
        .action-btn {
            width: 100px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="vendor.php">
                <i class="bi bi-shop me-2"></i>Vendor Panel
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="market.php">Market</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_products_vendor.php">Manage Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="sales_report_vendor.php">Sales Reports</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item profile-dropdown">
                        <div class="dropdown">
                            <button class="profile-btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="profile-img">
                                    <?php echo strtoupper(substr($vendorData['name'], 0, 1)); ?>
                                </div>
                                <span><?php echo htmlspecialchars($vendorData['name']); ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <div class="user-info">
                                        <div class="user-name"><?php echo htmlspecialchars($vendorData['name']); ?></div>
                                        <div class="user-email"><?php echo htmlspecialchars($vendorData['email']); ?></div>
                                        <span class="user-role"><?php echo ucfirst($vendorData['role']); ?></span>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="vendor_profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="vendor_settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="login.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Alerts for success/error messages -->
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Upload Product Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Product</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="vendor.php">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price (Ksh)</label>
                                    <input type="number" name="price" class="form-control" step="0.01" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" name="stock" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                            </div>
                            
                            <button type="submit" name="upload_product" class="btn btn-success w-100">
                                <i class="bi bi-upload me-2"></i>Upload Product
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Product List Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-list-ul me-2"></i>Your Products</h4>
                        <a href="vendor.php?download_report=true" class="btn btn-light btn-sm">
                            <i class="bi bi-download me-1"></i> Sales Report
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['product_id']) ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($product['product_name']) ?></strong>
                                                <?php if (!empty($product['description'])): ?>
                                                    <div class="text-muted small text-truncate" style="max-width: 200px;">
                                                        <?= htmlspecialchars($product['description']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>Ksh <?= number_format($product['price'], 2) ?></td>
                                            <td>
                                                <span class="badge bg-<?= ($product['stock'] > 0) ? 'success' : 'danger' ?>">
                                                    <?= htmlspecialchars($product['stock']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="vendor.php?delete_id=<?= $product['product_id'] ?>" 
                                                   class="btn btn-danger btn-sm action-btn"
                                                   onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                new bootstrap.Alert(alert).close();
            });
        }, 5000);
    </script>
</body>
</html>