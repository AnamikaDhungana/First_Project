<!-- 
session_start();
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../Login/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
    <header>
               Admin navigation as in auth_admin.php 
        <nav>
            <ul>
                <li><a href="auth_admin.php">Dashboard</a></li>
                <li><a href="manage_products.php">Manage Products</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section class="manage-users">
        <h1>Manage Users</h1>

        
        // Connect to the database and retrieve user data
        include('database_connection.php');
        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='user'>";
                echo "<p>" . $row['email'] . "</p>";
                echo "<a href='edit_user.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_user.php?id=" . $row['id'] . "'>Delete</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No users available.</p>";
        }
        ?>
    </section>
</body>
</html> -->

<?php
include('database_connection.php');

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row['username'] . "</h3>";
        echo "<p>Email: " . $row['email'] . "</p>";
        echo "<a href='delete_user.php?id=" . $row['id'] . "'>Delete</a>";
        echo "</div>";
    }
} else {
    echo "No users found.";
}
?>
