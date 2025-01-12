<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link  rel="stylesheet" href="products.css" >
</head>

<body>

    <!-- Header -->
    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

    <!-- Main Content -->
    <main>
        <section id="product-list" class="tea-section">
            <h2>Our Products</h2>
            <div class="tea-grid">
                <?php
               require_once "../Login/database_connection.php";

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch product data
                $sql = "SELECT product_name, product_price, product_description, image_url FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="tea-item">';
                        echo '<img src="../Admin_Page/uploads/' . $row["image_url"] . '" alt="' . $row["product_name"] . '">';
                        echo '<h2>' . $row["product_name"] . '</h2>';
                        echo '<p>Price: Rs ' . $row["product_price"] . '</p>';
                        echo '<p>' . $row["product_description"] . '</p>';
                        echo '<button class="add-to-cart-btn">Add To Cart</button>';
                        echo '</div>';
                    }
                } else {
                    echo "No products available.";
                }

                $conn->close();
                ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.html")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script src="../language/script.js"></script>
</body>

</html>
