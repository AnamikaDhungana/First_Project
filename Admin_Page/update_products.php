<?php
session_start();
include('database_connection.php');

if (isset($_GET['update_id'])) {
    $product_id = $_GET['update_id'];
    
    // Fetch product details
    $query = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    
    if (!$product) {
        die("Product not found!");
    }
}

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $image = $_FILES['image']['name'];
    
    if ($image) {
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $update_query = "UPDATE products SET product_name=?, product_price=?, product_description=?, stock=?, image_url=?, category=? WHERE product_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sissssi", $product_name, $price, $description, $stock, $image, $category, $product_id);
    } else {
        $update_query = "UPDATE products SET product_name=?, product_price=?, product_description=?, stock=?, category=? WHERE product_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sisssi", $product_name, $price, $description, $stock, $category, $product_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='manage_products.php';</script>";
    } else {
        echo "Error updating product: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #F8F4E3;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 400px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #F8F4E3;
        }
        .form-container label {
            display: block;
            margin-bottom: 0.5rem;
        }
        .form-container input,
        .form-container textarea {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.50rem;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .form-container textarea {
            height: 100px;
            resize: vertical;
        }
        .form-container button {
            background-color: #2C5F2D;
            color: white;
            border: none;
            cursor: pointer;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 5px;
        }
        .form-container button:hover {
            background-color: #C5B358;
        }
        
    </style>
</head>
<body>
<!--JS for the Header-->

<div id="header-placeholder"></div>
    <script>
        fetch('../Admin_Page/admin_header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

<div class="form-container">
    <h2>Update Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $product['product_name']; ?>" required>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" value="<?php echo $product['product_price']; ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description"><?php echo $product['product_description']; ?></textarea>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="<?php echo $product['stock']; ?>" required>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image">
        <p>Current Image: <?php echo $product['image_url']; ?></p>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?php echo $product['category']; ?>" required>

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>