<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="products.css">
</head>

<body>

    <!-- Header -->
    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.php') 
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

    <!-- Main Content -->
    <main>
        <?php
        require_once "../Login/database_connection.php";

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Section arrays to define categories
        $categories = [
            "Black Tea" => "black-tea",
            "Flower Tea" => "flower-tea",
            "Herbal Tea" => "herbal-tea",
            "Green Tea" => "green-tea"
        ];

        foreach ($categories as $categoryName => $sectionId) {
            echo "<section id='$sectionId' class='tea-section'>";
            echo "<h2>$categoryName</h2>";
            echo "<div class='tea-grid'>";

            // Fetch products for the current category
            $sql = "SELECT product_name, product_price, product_description, image_url FROM products WHERE category = '$categoryName'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output each product
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="tea-item">';
                    echo '<img src="../Admin_Page/uploads/' . $row["image_url"] . '" alt="' . $row["product_name"] . '">';
                    echo '<h2>' . $row["product_name"] . '</h2>';
                    echo '<p>Price: Rs ' . $row["product_price"] . '</p>';
                    echo '<p>' . $row["product_description"] . '</p>';
                    echo '<button class="add-to-cart-btn" onclick="addToCart(\'' . $row["product_name"] . '\', ' . $row["product_price"] . ', \'../Admin_Page/uploads/' . $row["image_url"] . '\')">Add To Cart</button>';
                    echo '</div>';
                }
            } else {
                echo "<p>No products available in this category.</p>";
            }

            echo "</div>";
            echo "</section>";
        }

        $conn->close();
        ?>
    </main>

    <!-- Footer -->
    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script>
        function addToCart(productName, productPrice, productImage) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Check if the product is already in the cart
            const productIndex = cart.findIndex(item => item.name === productName);
            if (productIndex === -1) {
                cart.push({ name: productName, price: productPrice, image: productImage, quantity: 1 });
            } else {
                cart[productIndex].quantity += 1;
            }

            // Save the updated cart to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            // Redirect to the cart page
            window.location.href = "/Header/add_to_cart.html";
        }
    </script>
</body>

</html>
