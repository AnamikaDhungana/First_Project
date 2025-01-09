<?php

include('database_connection.php');
// Fetch all products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$products = [];
while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
}

// Return products as JSON
header('Content-Type: application/json');
echo json_encode($products);

?>