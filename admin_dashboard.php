<?php
session_start();
include __DIR__ . '/connectDb.php'; // Database connection

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch dashboard statistics
$stats = [];

// Total users
$userQuery = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userQuery);
$stats['total_users'] = $userResult->fetch_assoc()['total_users'];

// Total products
$productQuery = "SELECT COUNT(*) AS total_products FROM products";
$productResult = $conn->query($productQuery);
$stats['total_products'] = $productResult->fetch_assoc()['total_products'];

// Total sales
$salesQuery = "SELECT COUNT(*) AS total_sales, SUM(quantity) AS total_items_sold, 
                SUM(quantity * (SELECT price FROM products WHERE products.product_id = sales.product_id)) AS total_revenue 
                FROM sales";
$salesResult = $conn->query($salesQuery);
$salesData = $salesResult->fetch_assoc();
$stats['total_sales'] = $salesData['total_sales'];
$stats['total_items_sold'] = $salesData['total_items_sold'];
$stats['total_revenue'] = $salesData['total_revenue'] ?? 0;

// Recent sales (last 7 days)
$recentSalesQuery = "SELECT s.sale_id, p.product_name, s.quantity, s.sale_date, u.name AS vendor_name
                     FROM sales s
                     JOIN products p ON s.product_id = p.product_id
                     JOIN users u ON s.vendor_id = u.id
                     ORDER BY s.sale_date DESC LIMIT 5";
$recentSalesResult = $conn->query($recentSalesQuery);
$recentSales = [];
while ($row = $recentSalesResult->fetch_assoc()) {
    $recentSales[] = $row;
}

// Sales data for charts (last 30 days)
$chartQuery = "SELECT DATE(sale_date) AS day, COUNT(*) AS sales_count, 
               SUM(quantity) AS items_sold,
               SUM(quantity * (SELECT price FROM products WHERE products.product_id = sales.product_id)) AS daily_revenue
               FROM sales 
               WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
               GROUP BY DATE(sale_date)";
$chartResult = $conn->query($chartQuery);
$salesChartData = [];
while ($row = $chartResult->fetch_assoc()) {
    $salesChartData[] = $row;
}

// Product ratings
$ratingsQuery = "SELECT p.product_name, AVG(r.rating) AS avg_rating
                 FROM ratings r
                 JOIN products p ON r.product_id = p.product_id
                 GROUP BY p.product_id
                 ORDER BY avg_rating DESC LIMIT 5";
$ratingsResult = $conn->query($ratingsQuery);
$topRatedProducts = [];
while ($row = $ratingsResult->fetch_assoc()) {
    $topRatedProducts[] = $row;
}

// Payment status
$paymentQuery = "SELECT status, COUNT(*) AS count FROM payments GROUP BY status";
$paymentResult = $conn->query($paymentQuery);
$paymentStatus = [];
while ($row = $paymentResult->fetch_assoc()) {
    $paymentStatus[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .card-counter {
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            border-radius: 5px;
            transition: .3s linear all;
        }
        .card-counter:hover {
            box-shadow: 4px 4px 20px #DADADA;
            transition: .3s linear all;
        }
        .card-counter.primary {
            background-color: #007bff;
            color: #FFF;
        }
        .card-counter.success {
            background-color: #28a745;
            color: #FFF;
        }
        .card-counter.warning {
            background-color: #ffc107;
            color: #FFF;
        }
        .card-counter.danger {
            background-color: #dc3545;
            color: #FFF;
        }
        .card-counter.info {
            background-color: #17a2b8;
            color: #FFF;
        }
        .card-counter i {
            font-size: 3em;
            opacity: 0.3;
        }
        .card-counter .count-numbers {
            font-size: 2em;
            display: block;
        }
        .card-counter .count-name {
            font-style: italic;
            opacity: 0.8;
            display: block;
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }
body {
    /* Fallback solid color */
    background-color: #f8f9fa;
    
    /* Gradient 1: Light and professional */
    body {
    background: 
        linear-gradient(to left, green, blue);
    background-blend-mode: overlay;
    min-height: 100vh;
    margin: 0;
}

/* Add this to make cards more visible against gradient */
.card {
    background-color: rgba(235, 241, 244, 0.9);
    backdrop-filter: blur(5px);
    border: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.navbar {
    background-color: rgba(33, 37, 41, 0.95) !important;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="market.php">Market</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="sales_reports.php">Sales Reports</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <h1 class="text-center mb-4">Admin Dashboard</h1>
        
        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card-counter primary">
                    <i class="bi bi-people"></i>
                    <span class="count-numbers"><?php echo $stats['total_users']; ?></span>
                    <span class="count-name">Total Users</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-counter success">
                    <i class="bi bi-box-seam"></i>
                    <span class="count-numbers"><?php echo $stats['total_products']; ?></span>
                    <span class="count-name">Total Products</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-counter warning">
                    <i class="bi bi-cart-check"></i>
                    <span class="count-numbers"><?php echo $stats['total_sales']; ?></span>
                    <span class="count-name">Total Sales</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-counter info">
                    <i class="bi bi-currency-dollar"></i>
                    <span class="count-numbers">$<?php echo number_format($stats['total_revenue'], 2); ?></span>
                    <span class="count-name">Total Revenue</span>
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Sales Overview (Last 30 Days)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Payment Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="paymentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tables Row -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Sales</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Vendor</th>
                                        <th>Quantity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentSales as $sale): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sale['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($sale['vendor_name']); ?></td>
                                        <td><?php echo $sale['quantity']; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($sale['sale_date'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Top Rated Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Rating</th>
                                        <th>Stars</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topRatedProducts as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><?php echo number_format($product['avg_rating'], 1); ?></td>
                                        <td>
                                            <?php 
                                            $fullStars = floor($product['avg_rating']);
                                            $halfStar = ($product['avg_rating'] - $fullStars) >= 0.5;
                                            
                                            for ($i = 0; $i < $fullStars; $i++) {
                                                echo '<i class="bi bi-star-fill text-warning"></i>';
                                            }
                                            if ($halfStar) {
                                                echo '<i class="bi bi-star-half text-warning"></i>';
                                                $fullStars++;
                                            }
                                            for ($i = $fullStars; $i < 5; $i++) {
                                                echo '<i class="bi bi-star text-warning"></i>';
                                            }
                                            ?>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script>
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChartData = {
            labels: [<?php echo implode(',', array_map(function($item) { 
                return "'" . date('M j', strtotime($item['day'])) . "'"; 
            }, $salesChartData)); ?>],
            datasets: [
                {
                    label: 'Sales Count',
                    data: [<?php echo implode(',', array_column($salesChartData, 'sales_count')); ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                },
                {
                    label: 'Items Sold',
                    data: [<?php echo implode(',', array_column($salesChartData, 'items_sold')); ?>],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    yAxisID: 'y1'
                },
                {
                    label: 'Revenue ($)',
                    data: [<?php echo implode(',', array_column($salesChartData, 'daily_revenue')); ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    type: 'line',
                    yAxisID: 'y2'
                }
            ]
        };
        
        const salesChart = new Chart(salesCtx, {
            type: 'bar',
            data: salesChartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Sales Count'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Items Sold'
                        }
                    },
                    y2: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Revenue ($)'
                        },
                        min: 0
                    }
                }
            }
        });
        
        // Payment Status Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php echo implode(',', array_map(function($item) { 
                    return "'" . htmlspecialchars($item['status']) . "'"; 
                }, $paymentStatus)); ?>],
                datasets: [{
                    data: [<?php echo implode(',', array_column($paymentStatus, 'count')); ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 205, 86, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>