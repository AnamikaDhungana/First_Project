<?php
require_once "../Login/database_connection.php";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teapot & Accessories</title>
    <link rel="stylesheet" href="accessory.css">
</head>

<body>
    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });
    </script>

    <section class="header">
        <h2 id="title_of_accessories">"Brew in Style: Elegant Teapots & Timeless Accessories"</h2>
        <p id="sub_title">Enhance your tea experience with our carefully crafted teapot sets and unique accessories.</p>
    </section>

    <section class="product-section">
        <?php
        $sql = "SELECT product_name, product_price, product_description, image_url FROM products WHERE category = 'Accessories'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product1">';
                echo '<img src="../Admin_Page/uploads/' . $row["image_url"] . '" alt="' . $row["product_name"] . '" class="product-img">';
                echo '<h1 class="product-title">' . $row["product_name"] . '</h1>';
                echo '<p class="product-price">Rs. ' . $row["product_price"] . '</p>';
                echo '<button class="add-to-cart-btn" onclick="addToCart(\'' . $row["product_name"] . '\', ' . $row["product_price"] . ', \'../Admin_Page/uploads/' . $row["image_url"] . '\')">Add to cart</button>';
                echo '<p class="product-description">' . $row["product_description"] . '</p>';
                echo '</div>';
            }
        } else {
            echo "<p>No accessories available.</p>";
        }
        ?>
    </section>

    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script src="./../language/script.js"></script>
    <?php $conn->close(); ?>
</body>

</html>
