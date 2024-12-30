<?php
// Include the database connection file
include 'db_connection.php';  // Make sure this file is in the same folder or provide the correct path

// Create the `users` table
$sql_users = " CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql_users)) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating 'users' table: " . mysqli_error($conn) . "<br>";
}

// Create the `products` table
$sql_products = " CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    product_price DECIMAL(10, 2) NOT NULL,
    product_description TEXT,
    stock INT DEFAULT 0,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (mysqli_query($conn, $sql_products)) {
    echo "Table 'products' created successfully.<br>";
} else {
    echo "Error creating 'products' table: " . mysqli_error($conn) . "<br>";
}

// Create the `orders` table
$sql_orders = " CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";
if (mysqli_query($conn, $sql_orders)) {
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating 'orders' table: " . mysqli_error($conn) . "<br>";
}

// // Create the `order_details` table
// $sql_order_details = " CREATE TABLE IF NOT EXISTS order_details (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     order_id INT NOT NULL,
//     product_id INT NOT NULL,
//     quantity INT NOT NULL,
//     price DECIMAL(10, 2) NOT NULL,
//     FOREIGN KEY (order_id) REFERENCES orders(order_id),
//     FOREIGN KEY (product_id) REFERENCES products(product_id)
// )";
// if (mysqli_query($conn, $sql_order_details)) {
//     echo "Table 'order_details' created successfully.<br>";
// } else {
//     echo "Error creating 'order_details' table: " . mysqli_error($conn) . "<br>";
// }

// Create the `payments` table
$sql_payments = " CREATE TABLE IF NOT EXISTS payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    payment_amount DECIMAL(10, 2) NOT NULL,
    payment_mode VARCHAR(50) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
)";
if (mysqli_query($conn, $sql_payments)) {
    echo "Table 'payments' created successfully.<br>";
} else {
    echo "Error creating 'payments' table: " . mysqli_error($conn) . "<br>";
}

// Close the database connection
mysqli_close($conn);
?>
