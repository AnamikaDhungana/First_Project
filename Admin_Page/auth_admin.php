<?php
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['userRole'] !== 'Admin') {
    header("Location: ../Login/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_style.css"> <!-- External CSS file for styling -->
</head>
<body>
    <!-- Admin Navigation Bar -->
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

    <!-- Main Content Section -->
    <section class="admin-dashboard">
        <h1>Welcome, Admin!</h1>
        <p>Manage your website's content, products, and users from here.</p>
        
        <!-- Quick Stats or Information -->
        <div class="stats">
            <div class="stat-item">
                <h3>Total Products</h3>
                <p>19</p>
            </div>
            <div class="stat-item">
                <h3>Total Users</h3>
                <p>200</p>
            </div>
        </div>
    </section>
</body>
</html>
