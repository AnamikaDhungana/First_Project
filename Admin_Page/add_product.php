<?php
include('../database_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Step 2: Reset AUTO_INCREMENT to 1 (resetting after deletion or new insertion)
    $resetQuery = "ALTER TABLE products AUTO_INCREMENT = 1"; // Reset the AUTO_INCREMENT value
    if ($conn->query($resetQuery) === TRUE) {
        // echo "Product table cleared and AUTO_INCREMENT reset.";
    } else {
        echo "Error resetting AUTO_INCREMENT: " . $conn->error;
    }
    // Retrieve form data
    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    $category = $_POST['category'];

    // Check if the uploads directory exists and is writable
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true); // Create the directory if it doesn't exist
    }

    if (!is_writable('uploads')) {
        die("Error: 'uploads' directory is not writable. Please check permissions.");
    }

    // Use a prepared statement to insert into the database
    $stmt = $conn->prepare("INSERT INTO products (product_name, product_price, product_description, stock, image_url, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $product_name, $price, $description, $stock, $image, $category);

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
    font-family: 'Times New Roman', Times, serif;
    background-color:#F8F4E3;
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

/* .form-container input,
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
} */
.form-container input,
.form-container textarea {
    width: 100%;
    margin-bottom: 1rem;
    padding: 0.50rem; /* Increased padding for better spacing */
    font-size: 1rem; /* Increased font size for readability */
    border: 1px solid #ccc; /* Added border for better definition */
    border-radius: 5px; /* Rounded corners */
    box-sizing: border-box; /* Ensures padding doesn't affect width */
}

.form-container textarea {
    height: 100px; /* Adjust height for text area */
    resize: vertical; /* Allow resizing vertically only */
}

.form-container button {
    background-color:  #2C5F2D;
    color: white;
    border: none;
    cursor: pointer;
    padding: 0.75rem; /* Consistent padding */
    font-size: 1rem; /* Consistent font size */
    border-radius: 5px; /* Rounded corners */
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
    <h2>Add a New Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="price">Price:</label>
        <input type="number" name="price" id="price" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description"></textarea>

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