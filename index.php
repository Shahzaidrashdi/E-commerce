<?php
require_once 'config.php';

// Get featured products
$featured_products = $conn->query("SELECT * FROM products WHERE stock > 0 ORDER BY RAND() LIMIT 4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shop - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
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
        }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">ShopOnline</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="details.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="cart.php">
                            <i class="bi bi-cart"></i>
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> My Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><a class="dropdown-item" href="orders.php">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php"><i class="bi bi-person-plus"></i> Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Welcome to ShopOnline</h1>
            <p class="lead">Discover amazing products at great prices</p>
            <a href="products.php" class="btn btn-primary btn-lg">Shop Now</a>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="container mb-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row">
            <?php while ($product = $featured_products->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card product-card h-100">
                        <img src="images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                             class="card-img-top product-img p-3" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted"><?php echo substr(htmlspecialchars($product['description']), 0, 50); ?>...</p>
                            <p class="card-text fw-bold">$<?php echo number_format($product['price'], 2); ?></p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="products.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                            <a href="cart.php?action=add&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4">
            <a href="products.php" class="btn btn-outline-primary">View All Products</a>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-4">Shop by Category</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="bi bi-phone" style="font-size: 2rem;"></i>
                            <h5 class="card-title mt-2">Electronics</h5>
                            <a href="products.php?category=Electronics" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="bi bi-tshirt" style="font-size: 2rem;"></i>
                            <h5 class="card-title mt-2">Clothing</h5>
                            <a href="products.php?category=Clothing" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <i class="bi bi-bag" style="font-size: 2rem;"></i>
                            <h5 class="card-title mt-2">Accessories</h5>
                            <a href="products.php?category=Accessories" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>ShopOnline</h5>
                    <p>Your one-stop shop for all your needs.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Home</a></li>
                        <li><a href="products.php" class="text-white">Products</a></li>
                        <li><a href="details.php" class="text-white">About Us</a></li>
                        <li><a href="contact.php" class="text-white">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address>
                        <p>123 Shop Street<br>Shopping City, SC 12345<br>Email: info@shoponline.com<br>Phone: (123) 456-7890</p>
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?php echo date('Y'); ?> ShopOnline. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>