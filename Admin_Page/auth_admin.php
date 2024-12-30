<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.html");
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
 <!-- External CSS file for styling -->
</head>

<body>
    <!-- Admin Navigation Bar -->
    <header>
        <nav>
            <ul>
                <li><a href="auth_admin.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="add_product.php">Add Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content Section -->
    <section class="admin-dashboard">
        <h1>Welcome, Admin!</h1>
        <p>Manage your website's content, products, and users from here.</p>
        
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