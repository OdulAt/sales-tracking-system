<?php
include __DIR__ . '/connectDb.php'; // Database connection
session_start();

// Check if user is logged in
$userData = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $userData = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Initialize search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Base query with average ratings and rating counts
$query = "SELECT p.*, 
          (SELECT AVG(rating) FROM ratings WHERE product_id = p.product_id) as avg_rating,
          (SELECT COUNT(*) FROM ratings WHERE product_id = p.product_id) as rating_count
          FROM products p";

// Add search condition if search term exists
if (!empty($searchTerm)) {
    $query .= " WHERE p.product_name LIKE ?";
}

// Order by average rating (highest first), then by rating count (most reviews first)
$query .= " ORDER BY avg_rating DESC, rating_count DESC, created_at DESC";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind search parameter if needed
if (!empty($searchTerm)) {
    $searchParam = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $searchParam);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market | NetSoko</title>
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
        
        .navbar-brand {
            font-weight: 700;
            color: var(--accent-color) !important;
        }
        
        .product-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
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
        
        .price-tag {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--accent-color);
        }
        
        .stock-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .rating-stars {
            color: #ffc107;
        }
        
        .search-box {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .btn-buy {
            background-color: var(--accent-color);
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
        }
        
        .btn-buy:hover {
            background-color: #d91a6d;
        }
        
        .btn-rate {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 20px;
        }
        
        .btn-rate:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .product-meta {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .modal-content {
            border-radius: 10px;
            border: none;
        }
        
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sort-options {
            max-width: 500px;
            margin: 0 auto 20px;
        }
        
        .no-results {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        /* Disabled buttons */
        .btn-disabled {
            opacity: 0.65;
            cursor: not-allowed;
            pointer-events: none;
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
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-cart3 me-2"></i>NetSoko
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($userData): ?>
                            <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3 py-1" href="sign_up.php">
                                <i class="bi bi-box-arrow-right"></i> BACK
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="text-center mb-3">
            <h1 class="fw-bold">Discover Amazing Products</h1>
            <p class="text-muted">Shop the highest rated items from our marketplace</p>
        </div>
        
        <!-- Search Form -->
        <form method="GET" action="openmarket.php" class="mb-4">
            <div class="search-box my-4">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control rounded-pill" 
                           placeholder="Search products..." 
                           value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button class="btn btn-primary rounded-pill" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if (!empty($searchTerm)): ?>
                        <a href="openmarket.php" class="btn btn-outline-secondary rounded-pill ms-2">Clear</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <!-- Product Listings -->
        <div class="row g-4">
            <?php 
            if ($result->num_rows > 0) {
                while ($product = $result->fetch_assoc()) { 
                    $avg_rating = $product['avg_rating'] ? round($product['avg_rating'], 1) : 0;
                    $rating_count = $product['rating_count'] ? $product['rating_count'] : 0;
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card card h-100">
                        <?php if ($product['stock'] > 0) { ?>
                            <span class="stock-badge bg-success">In Stock</span>
                        <?php } else { ?>
                            <span class="stock-badge bg-secondary">Out of Stock</span>
                        <?php } ?>
                        
                        <img src="<?php echo $product['image_url'] ? $product['image_url'] : 'https://via.placeholder.com/300x200?text=Product+Image'; ?>" 
                             class="card-img-top product-img" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($product['product_name']); ?></h5>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="price-tag">Ksh <?php echo number_format($product['price'], 2); ?></span>
                                
                                <div class="rating-stars">
                                    <i class="bi bi-star-fill"></i> <?php echo $avg_rating; ?>
                                    <small class="text-muted">(<?php echo $rating_count; ?>)</small>
                                </div>
                            </div>
                            
                            <p class="card-text text-truncate-2 small text-muted mb-3">
                                <?php echo htmlspecialchars($product['description'] ?: 'No description available'); ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <?php if ($product['stock'] > 0) { ?>
                                    <button class="btn btn-buy text-white btn-disabled">
                                        <i class="bi bi-cart-plus"></i> Buy Now
                                    </button>
                                <?php } else { ?>
                                    <button class="btn btn-secondary px-3 btn-disabled">
                                        <i class="bi bi-cart-x"></i> Out of Stock
                                    </button>
                                <?php } ?>
                                
                                <button class="btn-rate btn btn-disabled">
                                    <i class="bi bi-star"></i> Rate
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent product-meta">
                            <small>Posted <?php echo date('M d, Y', strtotime($product['created_at'])); ?></small>
                        </div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 no-results">';
                if (!empty($searchTerm)) {
                    echo '<h4>No products found for "' . htmlspecialchars($searchTerm) . '"</h4>';
                    echo '<p>Try a different search term</p>';
                } else {
                    echo '<h4>No products available at the moment</h4>';
                }
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Rating Form Modal -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Rate This Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="rate_product.php" method="POST" id="ratingForm">
                        <input type="hidden" id="product_id" name="product_id">
                        
                        <div class="text-center mb-4">
                            <div class="rating-input">
                                <input type="radio" id="star5" name="rating" value="5" required>
                                <label for="star5" class="bi bi-star-fill fs-1 mx-1"></label>
                                
                                <input type="radio" id="star4" name="rating" value="4">
                                <label for="star4" class="bi bi-star-fill fs-1 mx-1"></label>
                                
                                <input type="radio" id="star3" name="rating" value="3">
                                <label for="star3" class="bi bi-star-fill fs-1 mx-1"></label>
                                
                                <input type="radio" id="star2" name="rating" value="2">
                                <label for="star2" class="bi bi-star-fill fs-1 mx-1"></label>
                                
                                <input type="radio" id="star1" name="rating" value="1">
                                <label for="star1" class="bi bi-star-fill fs-1 mx-1"></label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="review" class="form-label">Optional Review</label>
                            <textarea class="form-control" id="review" name="review" rows="3" placeholder="Share your experience..."></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2">
                                <i class="bi bi-send"></i> Submit Rating
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to open rating modal
        function openRatingForm(productId) {
            document.getElementById('product_id').value = productId;
            var modal = new bootstrap.Modal(document.getElementById('ratingModal'));
            modal.show();
        }
        
        // Star rating interaction
        document.querySelectorAll('.rating-input input').forEach(radio => {
            radio.addEventListener('change', function() {
                const stars = document.querySelectorAll('.rating-input label');
                const rating = parseInt(this.value);
                
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('text-warning');
                    } else {
                        star.classList.remove('text-warning');
                        star.classList.add('text-secondary');
                    }
                });
            });
        });
        
        // Initialize stars as unselected
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating-input label');
            stars.forEach(star => {
                star.classList.add('text-secondary');
            });
        });
    </script>
</body>
</html>