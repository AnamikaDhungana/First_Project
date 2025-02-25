<?php
session_start();
include('../database_connection.php');

// Check if delete request is received
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Query to delete the product
    $delete_query = "DELETE FROM products WHERE product_id = $delete_id";
    
    if (mysqli_query($conn, $delete_query)) {
        // echo "<p>Product deleted successfully!</p>";
    } else {
        echo "<p>Error deleting product: " . mysqli_error($conn) . "</p>";
    }
}

// Check if edit form is submitted
if (isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Update query to modify the product
    $update_query = "UPDATE products SET product_name = '$product_name', product_price = '$product_price' WHERE product_id = $product_id";

    if (mysqli_query($conn, $update_query)) {
        echo "<p>Product updated successfully!</p>";
    } else {
        echo "<p>Error updating product: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
        body {
            font-family:'Times New Roman', Times, serif;
            background-color: #F8F4E3;
            margin: 0;
            padding: 0;
        }

        
        /* header nav ul li a:hover {
            background-color: #007bff;
        } */

        .container {
            max-width: 700px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 5px;
            background-color: #F8F4E3;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 1rem;
        }

        .product {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #F8F4E3;
        }

        .product p {
            margin: 0.5rem 0;
        }

        .product form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product form input[type="text"],
        .product form input[type="number"] {
            padding: 0.5rem;
            padding-right:5rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            max-width:970;
            font-family:'Times New Roman', Times, serif;
            font-size:1rem;
        }

        .product form button {
            padding: 0.6rem;
            background-color:  #2C5F2D;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
            /* max-width:970px; */
        }

        .product form button:hover {
            background-color:  #C5B358;
        }

        .actions {
            margin-top: 1rem;
        }

        .actions a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            margin-right: 5px;
        }
        .actions a.update {
    background-color: #007bff;
}

.actions a.update:hover {
    background-color: #0056b3;
}


        .actions a.delete {
            background-color:  #dc3545;
        }

        .actions a.delete:hover {
            /* opacity: 0.8; */
            background-color: #C5B358;
        }

        

        .add-product-link {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 10px 15px;
            background-color:  #2C5F2D;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .add-product-link:hover {
            background-color:  #C5B358;
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

<section class="container">
    <h1>Manage Products</h1>
    <a href="add_product.php" class="add-product-link">Add New Product</a>

    <?php
    // Check database connection
    if (!$conn) {
        die('<p>Error: Unable to connect to the database.</p>');
    }

    // Query to retrieve products
    $sql = "SELECT * FROM products";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product'>";
            echo "<p><strong>" . htmlspecialchars($row['product_name']) . "</strong></p>";
            echo "<p>Price: Rs." . htmlspecialchars($row['product_price']) . "</p>";
            
            // Edit Product Form
            echo "<form action='' method='POST'>";
            echo "<input type='hidden' name='product_id' value='" . $row['product_id'] . "'>";
            echo "<input type='text' name='product_name' value='" . htmlspecialchars($row['product_name']) . "' required placeholder='Product Name'>";
            echo "<input type='number' name='product_price' value='" . htmlspecialchars($row['product_price']) . "' required placeholder='Product Price'>";
          
            echo "</form>";

            // Delete Product
            echo "<div class='actions'>";
            echo "<a href='update_products.php?update_id=" . $row['product_id'] . "' class='update'>Update</a>";

            echo "<a href='?delete_id=" . $row['product_id'] . "' class='delete' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>";

           
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No products available.</p>";
    }
    ?>
</section>
</body>
</html>