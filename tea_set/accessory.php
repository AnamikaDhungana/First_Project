<?php
require_once "../database_connection.php";
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
    <!-- Js Header -->
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
        session_start();
        $language = "en";
        if (isset($_GET['language'])) {
            $language = $_GET['language'];
        }
        
        $sql = "SELECT product_id, product_name, product_name_np, product_price, product_price_np, product_description, product_description_np, image_url FROM products WHERE category = 'Accessories'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product1">';
                echo '<img src="../Admin_Page/uploads/' . $row["image_url"] . '" alt="' . $row["product_name"] . '" class="product-img">';
                
                // Check if the session variable is set before using it
                if ($language != "np") {
                    echo '<h1 class="product-title">' . $row["product_name_np"] . '</h1>';
                } else {
                    echo '<h1 class="product-title">' . $row["product_name"] . '</h1>';
                }
            
                if ($language != "np") {
                    echo '<p>मूल्य: रु.' . $row["product_price_np"] . '</p>';
                } else {
                    echo '<p>Price: Rs ' . $row["product_price"] . '</p>';
                }
                echo '<button class="add-to-cart-btn add_to_cart" onclick="addToCart(\'' . $row["product_id"] . '\', ' . $row["product_price"] . ', \'../Admin_Page/uploads/' . $row["image_url"] . '\')">Add to cart</button>';
                
                if ($language != "np") {
                    echo '<p class="product-description">' . $row["product_description_np"] . '</p>';
                } else {
                    echo '<p class="product-description">' . $row["product_description"] . '</p>';
                }
                echo '</div>';
            }
        } else {
            echo "<p>No accessories available.</p>";
        }

        $conn->close();
        ?>
    </section>

    <!-- Js Footer -->
    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script>
        function addToCart(productId, price, imageUrl) {
            console.log("Url ::: " + ../Header/add_to_cart.php?action=add_to_cart&product_id=${productId}&quantity=1&price=${price});

            fetch(../Header/add_to_cart.php?action=add_to_cart&product_id=${productId}&quantity=1&price=${price})
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Item added to cart!');
                        // Instead of immediate redirect, consider showing a mini-cart or notification
                        window.location.href = "../Header/add_to_cart.php";
                    } else {
                        alert(data.message);
                        if (data.message === 'Please login first') {
                            window.location.href = "../Login/login.php";
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the item to cart' + error);
                });
        }
    </script>

    <script src="./../language/script.js"> </script>
</body>

</html>