<?php
session_start();
include('../database_connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family:Times New Roman;
            background-color:  #F8F4E3;
            margin: 0;
            padding: 0;
        }

        

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            border-radius: 5px;
            background-color:  #F8F4E3;
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
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }
    </style>
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

            echo '<a href="update_users.php?id=' . $row['id'] . '" class="edit">Update</a>';
            echo '<a href="delete_users.php?id=' . $row['id'] . '" class="delete" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
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