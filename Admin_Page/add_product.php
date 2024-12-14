<?php
include('database_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);

    // Check if the uploads directory exists and is writable
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true); // Create the directory if it doesn't exist
    }

    if (!is_writable('uploads')) {
        die("Error: 'uploads' directory is not writable. Please check permissions.");
    }

    // Use a prepared statement to insert into the database
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_price, product_description, image_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $product_name, $price, $description, $image);

    if ($stmt->execute()) {
        // Move the uploaded file
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
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }

        header nav ul {
            list-style-type: none;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin: 0 15px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav ul li a:hover {
            background-color: #007bff;
        }

        .form-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-container input,
        .form-container textarea,
        .form-container button {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.5rem;
        }

        .form-container button {
            background-color:#333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<!-- Admin Navigation Bar -->
<header>
    <nav>
           <ul>
                <li><a href="auth_admin.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="add_product.php">Add Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
    </nav>
</header>

<div class="form-container">
    <h2>Add a New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="image">Product Image:</label>
        <input type="file" name="image" id="image" required>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>

