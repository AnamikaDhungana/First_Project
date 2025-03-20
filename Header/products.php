<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link rel="stylesheet" href="./products.css">
</head>

<body class="body_class">

    <!--JS Header -->
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

    //         // Add the search link for the current category
    // echo "<a href='search.php?category=$categoryName'>Search in $categoryName</a>"; // <-- Add this line

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
                    echo '<button class="add-to-cart-btn add_to_cart" onclick="addToCart(\'' . $row["product_id"] . '\', ' . $row["product_price"] . ', \'../Admin_Page/uploads/' . $row["image_url"] . '\')">Add To Cart</button>';
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

    <!--Js Footer -->
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

            console.log("Url ::: " + `../Header/add_to_cart.php?action=add_to_cart&product_id=${productId}&quantity=1&price=${price}`);


    fetch(`../Header/add_to_cart.php?action=add_to_cart&product_id=${productId}&quantity=1&price=${price}`)
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
