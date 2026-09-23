<?php
session_start();
include __DIR__ . '/connectDb.php'; // Database connection

// Check if vendor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
    header('Location: login.php');
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

$message = $error = "";

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $image_url = trim($_POST['image_url']);

    if (!empty($name) && is_numeric($price) && is_numeric($stock)) {
        $stmt = $conn->prepare("INSERT INTO products (vendor_id, product_name, description, price, stock, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issdis", $vendor_id, $name, $description, $price, $stock, $image_url);
        if ($stmt->execute()) {
            $message = "Product added successfully!";
        } else {
            $error = "Failed to add product.";
        }
        $stmt->close();
    } else {
        $error = "Invalid product details!";
    }
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ? AND vendor_id = ?");
    $stmt->bind_param("ii", $id, $vendor_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product has been deleted.";
    } else {
        $_SESSION['error'] = "Failed to delete product.";
    }
    $stmt->close();
    header('Location: vendor.php');
    exit();
}

// Fetch vendor's products
$products = $conn->query("SELECT * FROM products WHERE vendor_id = $vendor_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        .product-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .product-img {
            height: 120px;
            object-fit: contain;
            background-color: #f5f5f5;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
        }
        
        .action-btn {
            width: 80px;
            margin: 2px;
        }
        
        .badge-vendor {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="vendor.php">
                <i class="bi bi-shop me-2"></i>Vendor Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="vendor.php">My Products</a></li>
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
        <!-- Display messages -->
        <?php if (!empty($_SESSION['message'])): ?>
            <script>Swal.fire('Success!', '<?= $_SESSION['message']; ?>', 'success');</script>
            <?php unset($_SESSION['message']); ?>
        <?php elseif (!empty($_SESSION['error'])): ?>
            <script>Swal.fire('Error!', '<?= $_SESSION['error']; ?>', 'error');</script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="bi bi-box-seam me-2"></i>My Products</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Product
                    </button>
                </div>

                <!-- Products Table -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $products->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $row['product_id'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($row['image_url'])): ?>
                                                        <img src="<?= htmlspecialchars($row['image_url']) ?>" 
                                                             class="product-img me-3" 
                                                             alt="<?= htmlspecialchars($row['product_name']) ?>"
                                                             style="width: 60px;">
                                                    <?php endif; ?>
                                                    <strong><?= htmlspecialchars($row['product_name']) ?></strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted small text-truncate" style="max-width: 200px;">
                                                    <?= !empty($row['description']) ? htmlspecialchars($row['description']) : 'No description' ?>
                                                </div>
                                            </td>
                                            <td>Ksh <?= number_format($row['price'], 2) ?></td>
                                            <td>
                                                <span class="badge bg-<?= ($row['stock'] > 0) ? 'success' : 'danger' ?>">
                                                    <?= $row['stock'] ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit_product_vendor.php?id=<?= $row['product_id'] ?>" 
                                                   class="btn btn-sm btn-warning action-btn">
                                                   <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <button onclick="confirmDelete(<?= $row['product_id'] ?>)" 
                                                        class="btn btn-sm btn-danger action-btn">
                                                        <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Add New Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="vendor.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (Ksh)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to undo this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'vendor.php?delete=' + id;
            }
        });
    }
    
    // Focus first input in modal when shown
    var addProductModal = document.getElementById('addProductModal');
    addProductModal.addEventListener('shown.bs.modal', function () {
        addProductModal.querySelector('input[name="name"]').focus();
    });
    </script>
</body>
</html>