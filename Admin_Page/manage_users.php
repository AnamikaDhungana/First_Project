<!-- 

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }

        header nav ul {
            list-style-type: none;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin: 0 15px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav ul li a:hover {
            background-color: #007bff;
        }
    </style>

</head>
<body>
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

    <section class="manage-users">
        <h1>Manage Users</h1>

        
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
</html>  -->

<?php
session_start();
include('database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }

        header nav ul {
            list-style-type: none;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin: 0 15px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav ul li a:hover {
            background-color: #007bff;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 5px;
        }

        .container h1 {
            text-align: center;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .actions a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .actions a.edit {
            background-color: #007bff;
        }

        .actions a.delete {
            background-color: #dc3545;
        }

        .actions a:hover {
            opacity: 0.8;
        }

        .search-bar {
            margin-bottom: 1rem;
            text-align: center;
        }

        .search-bar input {
            padding: 0.5rem;
            width: 300px;
        }

        .search-bar button {
            padding: 0.5rem;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
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

<div class="container">
    <h1>Manage Users</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="manage_users.php">
            <input type="text" name="search" placeholder="Search by name or email" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- User Table -->
    <?php
    /// Handle search functionality
    $searchQuery = '';
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $searchQuery = "WHERE email LIKE '%$search%' OR full_name LIKE '%$search%'";
    }

    // Retrieve users from database
    $sql = "SELECT * FROM users $searchQuery";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Action</th></tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['full_name'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td class="actions">';
            // echo '<a href="edit_user.php?id=' . $row['id'] . '" class="edit">Edit</a>';
            echo '<a href="delete_user.php?id=' . $row['id'] . '" class="delete" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No users found.</p>';
    }
    ?>
</div>
</body>
</html>
