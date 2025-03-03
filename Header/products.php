<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="./products.css">
</head>

<body class="body_class">

    <!--Js Header -->
    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.php') 
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

    <main>
        <?php
        require_once "../database_connection.php";
        session_start();
        $language = "en";
        if (isset($_GET['language'])) {
            $language = $_GET['language'];
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
            echo "<h2 id='" . $sectionId . "_1'>" . $categoryName . "</h2>";
            echo "<div class='tea-grid'>";

            // Fetch products for the current category
            $sql = "SELECT * FROM products WHERE category = '$categoryName'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
    
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="tea-item">';
                    echo '<img src="../Admin_Page/uploads/' . $row["image_url"] . '" alt="' . $row["product_name"] . ' <br>">';
                    if ($language != "np") {
                        echo '<h2>' . $row["product_name_np"] . '</h2>';
                    } else {
                        echo '<h2>' . $row["product_name"] . '</h2>';
                    }
                    
                    if ($language != "np") {
                        echo '<p>मूल्य: रु.' . $row["product_price_np"] . '</p>';
                    } else {
                        echo '<p>Price: Rs ' . $row["product_price"] . '</p>';
                    }
                    echo '<p>' . $row["product_description"] . '</p>';
                    echo '<button class="add-to-cart-btn add_to_cart" onclick="addToCart(\'' . addslashes($row["product_name"]) . '\', ' 
                    . $row["product_price"] . ', \'../Admin_Page/uploads/' . addslashes($row["image_url"]) . '\', \'' 
                    . addslashes($row["product_id"]) . '\')">Add To Cart</button>';
               
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
        function addToCart(productName, productPrice, productImage, productId) {
            // let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // // Check if the product is already in the cart
            // const productIndex = cart.findIndex(item => item.name === productName);
            // if (productIndex === -1) {
            //     cart.push({ name: productName, price: productPrice, image: productImage, quantity: 1 });
            // } else {
            //     cart[productIndex].quantity += 1;
            // }


            // // Save the updated cart to localStorage
            // localStorage.setItem('cart', JSON.stringify(cart));

            // window.location.href = "../Header/add_to_cart.php";
            const url = `add_to_cart.php?action=add_to_cart&product_id=${encodeURIComponent(productId)}&quantity=1&price=${encodeURIComponent(productPrice)}`;

fetch(url, {
    method: "GET",
})
.then(response => {
    if (!response.ok) {
        throw new Error("Failed to add product to cart");
    }
    return response.text(); // or response.json() if the server returns JSON
})
.then(data => {
    console.log("Product added to cart:", data);
    window.location.href = "../Header/add_to_cart.php"; // Redirect after success
})
.catch(error => {
    console.error("Error:", error);
    alert("Failed to add product to cart.");
});
        

        }
    </script>

<script src="./../language/script.js"> </script>
</body>

</html>
