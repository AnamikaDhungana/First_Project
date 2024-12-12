<?php
session_start();
// if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'admin') {
//     header("Location: ../Login/login.html");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
</head>
<body>
    <header>
    <nav>
            <ul>
                <li><a href="auth_admin.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="manage-products">
        <h1>Manage Products</h1>
        <a href="add_product.php">Add New Product</a>
        
        <?php
        include('database_connection.php');
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product'>";
                echo "<p>" . $row['name'] . "</p>";
                echo "<p>" . $row['price'] . "</p>";
                echo "<a href='edit_product.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_product.php?id=" . $row['id'] . "'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </section>
</body>
</html> 

<!-- 
include('database_connection.php');

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p>" . $row['description'] . "</p>";
        echo "<p>" . $row['price'] . "</p>";
        echo "<img src='uploads/" . $row['image'] . "' alt='Product Image'>";
        echo "<a href='edit_product.php?id=" . $row['id'] . "'>Edit</a>";
        echo "<a href='delete_product.php?id=" . $row['id'] . "'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "No products found.";
}
?> -->
