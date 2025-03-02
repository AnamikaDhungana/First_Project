<?php 
include('../database_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $product_name = trim($_POST['name_en']);
    $product_name_np = trim($_POST['name_np']);
    $product_price = trim($_POST['price_en']);
    $product_price_np = isset($_POST['price_np']) ? trim($_POST['price_np']) : "";
    $product_description = trim($_POST['description_en']);
    $product_description_np = trim($_POST['description_np']);
    $stock = intval($_POST['stock']);
    $category = trim($_POST['category']);
    
    // Convert Nepali numbers to English
    function convertNepaliToEnglish($num) {
        $nepaliNumbers = ['०','१','२','३','४','५','६','७','८','९'];
        $englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($nepaliNumbers, $englishNumbers, $num);
    }
    
    $product_name_np = convertNepaliToEnglish($product_name_np);
    
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    if (!is_writable('uploads')) {
        die("Error: 'uploads' directory is not writable. Please check permissions.");
    }
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO products 
        (product_name, product_name_np, product_price, product_price_np, product_description, product_description_np, stock, image_url, category) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssiss", $product_name, $product_name_np, $product_price, $product_price_np, $product_description, $product_description_np, $stock, $image, $category);
    
    if ($stmt->execute()) {
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<script>alert('Product added successfully!');</script>";
        } else {
            echo "Error: Unable to upload the image.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #F8F4E3;
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 500px;
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
            font-weight: bold;
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
            width: 100%;
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
    <h2>Add a New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="name_en">Product Name (English):</label>
        <input type="text" name="name_en" id="name_en" required>

        <label for="name_np">Product Name (Nepali):</label>
        <input type="text" name="name_np" id="name_np" required>

        <label for="price_en">Price (English):</label>
        <input type="text" name="price_en" id="price_en" required>

        <label for="price_np">Price (Nepali):</label>
        <input type="text" name="price_np" id="price_np" required>

        <label for="description_en">Description (English):</label>
        <textarea name="description_en" id="description_en"></textarea>

        <label for="description_np">Description (Nepali):</label>
        <textarea name="description_np" id="description_np"></textarea>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image" required>

        <label for="category">Category:</label>
        <input type="text" name="category" id="category" required>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>
