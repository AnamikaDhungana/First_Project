
<?php
include('database_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $target = "uploads/".basename($image);

    // Insert into the database
    $sql = "INSERT INTO products (name, price, description, image) VALUES ('$product_name', '$price', '$description', '$image')";

    if ($conn->query($sql) === TRUE) {
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        echo "Product added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="POST" enctype="multipart/form-data">

    <label for="name">Product Name:</label>
            <input type="text" name="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" required>

            <label for="description">Description:</label>
            <textarea name="description" required></textarea>

            <label for="image">Product Image:</label>
            <input type="file" name="image" required>

            <button type="submit">Add Product</button>
</form>
