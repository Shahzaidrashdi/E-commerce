<?php
require_once 'config.php'; // Database connection file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Online Shopping</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('images/about-hero.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
        .team-member {
            transition: transform 0.3s;
        }
        .team-member:hover {
            transform: translateY(-10px);
        }
        .team-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .timeline {
            position: relative;
            padding-left: 50px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #0d6efd;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -38px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #0d6efd;
            border: 4px solid white;
        }
    </style>
</head>
<body>
    <!-- Navigation -->

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Our Story</h1>
            <p class="lead">Discover who we are and what makes us different</p>
        </div>
    </section>

    <!-- About Content -->
    <div class="container">
        <!-- Mission Section -->
        <section class="row align-items-center mb-5">
            <div class="col-md-6">
                <h2 class="mb-4">Our Mission</h2>
                <p class="lead">To provide high-quality products with exceptional customer service at competitive prices.</p>
                <p>We believe shopping should be an enjoyable, hassle-free experience. That's why we've built our platform with you in mind, offering a wide selection of products, easy navigation, and fast delivery.</p>
            </div>
            <div class="col-md-6">
                <img src="images/mission.jpg" alt="Our Mission" class="img-fluid rounded shadow">
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-5 bg-light rounded mb-5">
            <div class="container">
                <h2 class="text-center mb-5">Why Choose Us?</h2>
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h4>Fast Delivery</h4>
                        <p>Get your orders delivered to your doorstep within 2-3 business days.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="feature-icon">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <h4>Easy Returns</h4>
                        <p>Not satisfied? Return within 30 days for a full refund.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Secure Shopping</h4>
                        <p>Your data is protected with industry-standard security measures.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- History Section -->
        <section class="mb-5">
            <h2 class="text-center mb-5">Our Journey</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <h4>2015 - Founded</h4>
                    <p>Started as a small online store with just 50 products in our inventory.</p>
                </div>
                <div class="timeline-item">
                    <h4>2017 - First Milestone</h4>
                    <p>Reached 10,000 happy customers and expanded our product range.</p>
                </div>
                <div class="timeline-item">
                    <h4>2020 - Major Expansion</h4>
                    <p>Launched our mobile app and introduced same-day delivery in major cities.</p>
                </div>
                <div class="timeline-item">
                    <h4>2023 - Today</h4>
                    <p>Serving over 1 million customers with 50,000+ products across multiple categories.</p>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="py-5 bg-light rounded mb-5">
            <div class="container">
                <h2 class="text-center mb-5">Our Core Values</h2>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><i class="bi bi-heart text-danger me-2"></i> Customer First</h4>
                                <p class="card-text">We put our customers at the center of everything we do, ensuring their satisfaction is our top priority.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><i class="bi bi-lightbulb text-warning me-2"></i> Innovation</h4>
                                <p class="card-text">We constantly seek new ways to improve and enhance your shopping experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><i class="bi bi-shield-check text-success me-2"></i> Integrity</h4>
                                <p class="card-text">We conduct our business with honesty, transparency, and ethical practices.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><i class="bi bi-people text-primary me-2"></i> Community</h4>
                                <p class="card-text">We believe in giving back and supporting the communities we serve.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>