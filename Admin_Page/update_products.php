<?php
session_start();
include('../database_connection.php');

// Function to convert Nepali numbers to English
function convertToEnglish($nepali_price) {
    $nepali_numbers = array('०', '१', '२', '३', '४', '५', '६', '७', '८', '९');
    $english_numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    return str_replace($nepali_numbers, $english_numbers, $nepali_price);
}

// Fetch product details for updating
$product = null;
if (isset($_GET['update_id'])) {
    $product_id = $_GET['update_id'];

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
    $product_name = $_POST['name_en'];
    $product_name_np = $_POST['name_np'];
    $product_price = $_POST['price_en'];
    $product_price_np = $_POST['price_np'];
    $product_description = $_POST['description_en'];
    $product_description_np = $_POST['description_np'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Convert Nepali price to English before storing
    $product_price = convertToEnglish($product_price);

    $image = $_FILES['image']['name'];
    $image_target = "uploads/" . basename($image);

    // Retain old image if no new one is uploaded
    if (!$image) {
        $image = $product['image_url'];
    } else {
        move_uploaded_file($_FILES['image']['tmp_name'], $image_target);
    }

    // Update query
    $update_query = "UPDATE products SET product_name=?, product_name_np=?, product_price=?, product_price_np=?, product_description=?, product_description_np=?, stock=?, image_url=?, category=? WHERE product_id=?";
    $stmt = $conn->prepare($update_query);
    
    $stmt->bind_param("ssssssissi", $product_name, $product_name_np, $product_price, $product_price_np, $product_description, $product_description_np, $stock, $image, $category, $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product updated successfully!'); window.location.href='manage_products.php';</script>";
    } else {
        die("Error updating product: " . $stmt->error);
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

        <label for="name_en">Product Name (English):</label>
        <input type="text" name="name_en" id="name_en" value="<?php echo $product['product_name']; ?>" required>

        <label for="name_np">Product Name (Nepali):</label>
        <input type="text" name="name_np" id="name_np" value="<?php echo $product['product_name_np']; ?>" required>

        <label for="price_en">Price (English):</label>
        <input type="text" name="price_en" id="price_en" value="<?php echo $product['product_price']; ?>" required>

        <label for="price_np">Price (Nepali):</label>
        <input type="text" name="price_np" id="price_np" value="<?php echo $product['product_price_np']; ?>" required>

        <label for="description_en">Description (English):</label>
        <textarea name="description_en" id="description_en"><?php echo $product['product_description']; ?></textarea>

        <label for="description_np">Description (Nepali):</label>
        <textarea name="description_np" id="description_np"><?php echo $product['product_description_np']; ?></textarea>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="<?php echo $product['stock']; ?>" required>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" value="<?php echo $product['category']; ?>" required>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image">
        <p>Current Image: <img src="uploads/<?php echo $product['image_url']; ?>" width="100" alt="Product Image"></p>

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>