<?php
require_once 'config.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    switch ($_GET['action']) {
        case 'add':
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            } else {
                // Get product details from database
                $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE id = ? AND stock > 0");
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $product = $result->fetch_assoc();
                    $_SESSION['cart'][$product_id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => 1
                    ];
                }
            }
            break;
            
        case 'remove':
            if (isset($_SESSION['cart'][$product_id])) {
                unset($_SESSION['cart'][$product_id]);
            }
            break;
            
        case 'increase':
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            }
            break;
            
        case 'decrease':
            if (isset($_SESSION['cart'][$product_id])) {
                if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                    $_SESSION['cart'][$product_id]['quantity']--;
                } else {
                    unset($_SESSION['cart'][$product_id]);
                }
            }
            break;
    }
    
    // Redirect to avoid resubmission
    header("Location: cart.php");
    exit;
}

// Calculate total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle checkout
if (isset($_POST['checkout']) && isset($_SESSION['user_id']) && !empty($_SESSION['cart'])) {
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Create order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
        $stmt->bind_param("id", $_SESSION['user_id'], $total);
        $stmt->execute();
        $order_id = $conn->insert_id;
        
        // Add order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        
        foreach ($_SESSION['cart'] as $item) {
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
            
            // Update product stock
            $conn->query("UPDATE products SET stock = stock - {$item['quantity']} WHERE id = {$item['id']}");
        }
        
        // Commit transaction
        $conn->commit();
        
        // Clear cart
        $_SESSION['cart'] = [];
        
        // Redirect to order confirmation
        header("Location: order_confirmation.php?id=$order_id");
        exit;
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        $error = "An error occurred during checkout. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ShopOnline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <div class="container mt-4">
        <h2 class="mb-4">Your Shopping Cart</h2>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (empty($_SESSION['cart'])): ?>
            <div class="alert alert-info">
                Your cart is empty. <a href="products.php">Continue shopping</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="images/products/product<?php echo $item['id']; ?>.jpg" 
                                             class="cart-item-img me-3" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <span><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="cart.php?action=decrease&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-dash"></i>
                                        </a>
                                        <span class="mx-2"><?php echo $item['quantity']; ?></span>
                                        <a href="cart.php?action=increase&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-plus"></i>
                                        </a>
                                    </div>
                                </td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th colspan="2">$<?php echo number_format($total, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="products.php" class="btn btn-outline-primary">Continue Shopping</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form method="POST" action="cart.php">
                        <button type="submit" name="checkout" class="btn btn-success">Proceed to Checkout</button>
                    </form>
                <?php else: ?>
                    <a href="login.php?redirect=cart.php" class="btn btn-success">Login to Checkout</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivrs