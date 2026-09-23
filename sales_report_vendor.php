<?php
session_start();
include __DIR__ . '/connectDb.php'; // Database connection

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'vendor') {
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];

// Fetch sales data with product and rating info for this vendor only
$query = "
    SELECT 
        p.product_name,
        COALESCE(SUM(s.quantity), 0) AS total_sales,
        p.stock - COALESCE(SUM(s.quantity), 0) AS stock_remaining,
        COALESCE(AVG(r.rating), 0) AS avg_rating,
        COUNT(r.rating_id) AS total_reviews
    FROM products p
    LEFT JOIN sales s ON p.product_id = s.product_id
    LEFT JOIN ratings r ON p.product_id = r.product_id
    WHERE p.vendor_id = ?
    GROUP BY p.product_name, p.stock
    ORDER BY total_reviews DESC, avg_rating DESC
";

// Prepare and execute with vendor_id parameter
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$sales = $stmt->get_result();

// Prepare data for graphs
$salesData = [];
while ($row = $sales->fetch_assoc()) {
    $salesData[] = [
        'product' => $row['product_name'],
        'total_sales' => $row['total_sales'],
        'stock_remaining' => $row['stock_remaining'],
        'avg_rating' => round($row['avg_rating'], 1),
        'total_reviews' => $row['total_reviews']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .table-bordered th, .table-bordered td { text-align: center; vertical-align: middle; }
        h1 { color: #2c3e50; font-family: 'Arial', sans-serif; }
        .btn-primary { background: linear-gradient(45deg, #4e73df, #224abe); }
        canvas { margin-bottom: 40px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="vendor_dashboard.php">Vendor Sales Report</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="manage_products_vendor.php">Manage Products</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="login.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center display-3 fw-bold">Your Sales Report</h1>
    <button class="btn btn-primary mb-3" onclick="window.print()">Print Report</button>

    <?php if (empty($salesData)): ?>
        <div class="alert alert-info">No sales data found for your products.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Total Sales</th>
                    <th>Stock Remaining</th>
                    <th>Avg Rating</th>
                    <th>Total Reviews</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salesData as $data): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($data['product']); ?></td>
                        <td><?php echo $data['total_sales']; ?></td>
                        <td><?php echo $data['stock_remaining']; ?></td>
                        <td><?php echo $data['avg_rating']; ?></td>
                        <td><?php echo $data['total_reviews']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Sales vs Products Graph -->
        <canvas id="salesChart"></canvas>
        <script>
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            const salesData = <?php echo json_encode($salesData); ?>;

            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: salesData.map(data => data.product),
                    datasets: [{
                        label: 'Total Sales',
                        data: salesData.map(data => data.total_sales),
                        backgroundColor: 'rgba(75, 192, 192, 0.8)'
                    }]
                },
                options: { responsive: true, plugins: { title: { display: true, text: 'Your Product Sales' } } }
            });
        </script>

        <!-- Ratings vs Products Graph -->
        <canvas id="ratingsChart"></canvas>
        <script>
            const ratingsCtx = document.getElementById('ratingsChart').getContext('2d');

            new Chart(ratingsCtx, {
                type: 'bar',
                data: {
                    labels: salesData.map(data => data.product),
                    datasets: [{
                        label: 'Average Rating',
                        data: salesData.map(data => data.avg_rating),
                        backgroundColor: 'rgba(255, 99, 132, 0.8)'
                    }]
                },
                options: { 
                    responsive: true, 
                    scales: { y: { beginAtZero: true, max: 5 } },
                    plugins: { title: { display: true, text: 'Your Product Ratings' } } 
                }
            });
        </script>
    <?php endif; ?>
</div>
</body>
</html>