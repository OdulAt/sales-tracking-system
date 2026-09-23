<?php
include __DIR__ . '/connectDb.php'; // Database connection

// Handle rating submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['rating'])) {
    $productId = intval($_POST['product_id']);
    $rating = intval($_POST['rating']);

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO ratings (product_id, rating) VALUES (?, ?)");
        $stmt->bind_param('ii', $productId, $rating);

        if ($stmt->execute()) {
            echo "<script>alert('Rating submitted successfully!'); window.location.href='market.php';</script>";
        } else {
            echo "<script>alert('Failed to submit rating. Please try again.');</script>";
        }
        $stmt->close();
    }
}

// Fetch all products
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Sales Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="market.php">Market</a></li>
                    <li class="nav-item"><a class="nav-link" href="sign_up.php">Sign Up</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white px-3" href="login.php">Log In</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Product Listings -->
    <div class="container mt-4">
        <h1 class="text-center">Marketplace</h1>
        <div class="row">
            <?php while ($product = $result->fetch_assoc()) { ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                            <p class="card-text">
                                <strong>Price:</strong> Ksh <?php echo number_format($product['price'], 2); ?><br>
                                <strong>Stock:</strong> <?php echo ($product['stock'] > 0) ? "Available" : "Out of Stock"; ?><br>
                                <small class="text-muted">Uploaded: <?php echo date('d M Y, H:i', strtotime($product['created_at'])); ?></small>
                            </p>
                            <?php if ($product['stock'] > 0) { ?>
                                <a href="manual.php?product_id=<?= $product['product_id']; ?>&amount=<?= $product['price']; ?>" class="btn btn-success">Buy Now</a>
                            <?php } ?>
                            <button class="btn btn-warning" onclick="openRatingForm(<?php echo $product['product_id']; ?>)">Rate Product</button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Rating Form Modal -->
    <div id="ratingModal" class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" id="product_id" name="product_id">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Select Rating:</label>
                            <select class="form-select" name="rating" required>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Rating</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openRatingForm(productId) {
            document.getElementById('product_id').value = productId;
            var modal = new bootstrap.Modal(document.getElementById('ratingModal'));
            modal.show();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>