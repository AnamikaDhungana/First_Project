<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    header {
    background-color: #2C5F2D;
    color: white;
    padding: 15px 0;
}

header nav ul {
   list-style-type: none;
   margin: 0;
   padding: 0;
   display: flex; /* Use flex for proper alignment */
   justify-content: left; /* Center align all links */
   align-items: center; /* Ensure vertical alignment */
   gap: 130px; /* Add space between links */
}

header nav ul li {
   margin: 0; /* Ensure no extra margin */
   padding: 0; /* Ensure no extra padding */
   margin-left:7px;
}

header nav ul li a {
   color: white;
   text-decoration: none;
   font-size: 1.2em;
   padding: 5px; /* Uniform top, bottom, left, right padding */
   border-radius: 5px;
   transition: background-color 0.3s;
   display: inline-block; /* Ensure consistent box model for links */
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

</body>
</html>