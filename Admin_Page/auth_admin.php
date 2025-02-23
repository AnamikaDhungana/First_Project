<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.php");
    exit();
}

// Include the database connection
include('database_connection.php');

// Query to count total products
$product_count_query = "SELECT COUNT(*) AS total_products FROM products";
$product_count_result = mysqli_query($conn, $product_count_query);
$product_count = 0;
if ($product_count_result && mysqli_num_rows($product_count_result) > 0) {
    $product_row = mysqli_fetch_assoc($product_count_result);
    $product_count = $product_row['total_products'];
}

// Query to count total users
$user_count_query = "SELECT COUNT(*) AS total_users FROM users";
$user_count_result = mysqli_query($conn, $user_count_query);
$user_count = 0;
if ($user_count_result && mysqli_num_rows($user_count_result) > 0) {
    $user_row = mysqli_fetch_assoc($user_count_result);
    $user_count = $user_row['total_users'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css?v=<?php echo time(); ?>">
    <!-- <link rel="icon" type="image/png" href="Layer_1.png"> -->
    <!-- External CSS file for styling -->
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
    <!-- Main Content Section -->
    <section class="admin-dashboard">
        <h1>Welcome, Admin!</h1>
        <!-- Quick Stats or Information -->
        <div class="stats">
            <div class="stat-item">
                <h3>Total Products</h3>
                <p><?php echo $product_count; ?></p>
            </div>
            <div class="stat-item">
                <h3>Total Users</h3>
                <p><?php echo $user_count; ?></p>
            </div>
        </div>
    </section>
</body>
</html>