<?php
require_once 'config.php';

// Get category filter if set
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? $_GET['search'] : null;

// Build query
$query = "SELECT * FROM products WHERE stock > 0";
$params = [];
$types = '';

if ($category) {
    $query .= " AND category = ?";
    $params[] = $category;
    $types .= 's';
}

if ($search) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}

$query .= " ORDER BY created_at DESC";

// Prepare and execute query
$stmt = $conn->prepare($query);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ShopOnline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .product-card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-img {
            height: 200px;
            object-fit: contain;
            padding: 10px;
        }
        .price {
            font-weight: bold;
            color: #0d6efd;
        }
        .out-of-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Our Products</h2>
                <?php if ($category): ?>
                    <p class="text-muted">Category: <?php echo htmlspecialchars($category); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <form class="d-flex" action="products.php" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Search products..." 
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
            </div>
        </div>

        <div class="row">
            <?php if ($products->num_rows > 0): ?>
                <?php while ($product = $products->fetch_assoc()): ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card product-card h-100">
                            <?php if ($product['stock'] <= 0): ?>
                                <span class="out-of-stock">Out of Stock</span>
                            <?php endif; ?>
                            <img src="images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 class="card-img-top product-img" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text text-muted"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</p>
                                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                            </div>
                            <div class="card-footer bg-white">
                                <?php if ($product['stock'] > 0): ?>
                                    <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Add to Cart</a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>Out of Stock</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No products found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>