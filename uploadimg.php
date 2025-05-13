<?php
require_once 'config.php';

// Initialize variables
$product = [
    'id' => '',
    'name' => '',
    'description' => '',
    'price' => '',
    'category' => '',
    'stock' => '',
    'image' => ''
];
$errors = [];
$success = '';

// Check if editing existing product
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $errors[] = "Product not found.";
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "Invalid CSRF token.";
    }

    // Get form data
    $product['id'] = isset($_POST['id']) ? intval($_POST['id']) : '';
    $product['name'] = trim($_POST['name']);
    $product['description'] = trim($_POST['description']);
    $product['price'] = trim($_POST['price']);
    $product['category'] = trim($_POST['category']);
    $product['stock'] = trim($_POST['stock']);
    $existing_image = $product['image'];

    // Validation
    if (empty($product['name'])) $errors[] = "Product name is required.";
    if (empty($product['description'])) $errors[] = "Description is required.";
    if (empty($product['price'])) {
        $errors[] = "Price is required.";
    } elseif (!is_numeric($product['price']) || $product['price'] <= 0) {
        $errors[] = "Price must be a positive number.";
    }
    if (empty($product['category'])) $errors[] = "Category is required.";
    if (empty($product['stock'])) {
        $errors[] = "Stock quantity is required.";
    } elseif (!ctype_digit($product['stock']) || $product['stock'] < 0) {
        $errors[] = "Stock must be a non-negative integer.";
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPG, PNG, and GIF images are allowed.";
        } elseif ($_FILES['image']['size'] > 2000000) { // 2MB
            $errors[] = "Image size must be less than 2MB.";
        } else {
            // Generate unique filename
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $upload_path = 'images/products/' . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if (!empty($existing_image) && file_exists('images/products/' . $existing_image)) {
                    unlink('images/products/' . $existing_image);
                }
                $product['image'] = $filename;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    // Save to database if no errors
    if (empty($errors)) {
        if (empty($product['id'])) {
            // Insert new product
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category, stock) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", 
                $product['name'],
                $product['description'],
                $product['price'],
                $product['image'],
                $product['category'],
                $product['stock']
            );
        } else {
            // Update existing product
            $stmt = $conn->prepare("UPDATE products SET 
                                   name = ?, description = ?, price = ?, category = ?, stock = ?
                                   " . (!empty($product['image']) ? ", image = ?" : "") . "
                                   WHERE id = ?");
            
            if (!empty($product['image'])) {
                $stmt->bind_param("ssdsisi", 
                    $product['name'],
                    $product['description'],
                    $product['price'],
                    $product['category'],
                    $product['stock'],
                    $product['image'],
                    $product['id']
                );
            } else {
                $stmt->bind_param("ssdsii", 
                    $product['name'],
                    $product['description'],
                    $product['price'],
                    $product['category'],
                    $product['stock'],
                    $product['id']
                );
            }
        }

        if ($stmt->execute()) {
            $success = empty($product['id']) ? "Product added successfully!" : "Product updated successfully!";
            
            // If new product, redirect to edit page
            if (empty($product['id'])) {
                $new_id = $conn->insert_id;
                header("Location: uploadimg.php?id=$new_id");
                exit;
            }
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo empty($product['id']) ? 'Add' : 'Edit'; ?> Product - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-container">
            <h2 class="mb-4"><?php echo empty($product['id']) ? 'Add New' : 'Edit'; ?> Product</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="uploadimg.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo htmlspecialchars($product['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Electronics" <?php echo $product['category'] == 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                            <option value="Clothing" <?php echo $product['category'] == 'Clothing' ? 'selected' : ''; ?>>Clothing</option>
                            <option value="Footwear" <?php echo $product['category'] == 'Footwear' ? 'selected' : ''; ?>>Footwear</option>
                            <option value="Accessories" <?php echo $product['category'] == 'Accessories' ? 'selected' : ''; ?>>Accessories</option>
                            <option value="Home" <?php echo $product['category'] == 'Home' ? 'selected' : ''; ?>>Home</option>
                            <option value="Other" <?php echo $product['category'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php 
                        echo htmlspecialchars($product['description']); 
                    ?></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price ($)</label>
                        <input type="number" class="form-control" id="price" name="price" 
                               value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" 
                               value="<?php echo htmlspecialchars($product['stock']); ?>" min="0" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/gif">
                    <?php if (!empty($product['image'])): ?>
                        <div class="mt-2">
                            <p>Current Image:</p>
                            <img src="images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                 class="preview-image" alt="Current product image">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="products.php" class="btn btn-secondary me-md-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>