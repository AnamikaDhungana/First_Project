<?php
include '../database_connection.php';  

// Create the `users` table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
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
$sql_products = "CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    product_price VARCHAR(50) NOT NULL,
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

// Add the `category` column before `created_at` if it doesn't exist
$check_category_column = "SHOW COLUMNS FROM products LIKE 'category'";
$result = mysqli_query($conn, $check_category_column);

if (mysqli_num_rows($result) == 0) {
    $add_category_column = "ALTER TABLE products ADD category VARCHAR(255) NOT NULL AFTER image_url";
    if (mysqli_query($conn, $add_category_column)) {
        echo "Column 'category' added successfully before 'created_at' in 'products' table.<br>";
    } else {
        echo "Error adding 'category' column: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Column 'category' already exists in 'products' table.<br>";
}

// Check if 'product_name_np' column exists in `products`
$check_product_name_np_column = "SHOW COLUMNS FROM products LIKE 'product_name_np'";
$result_product_name_np = mysqli_query($conn, $check_product_name_np_column);

if (mysqli_num_rows($result_product_name_np) == 0) {
    $alter_table = "ALTER TABLE products ADD COLUMN product_name_np VARCHAR(100) NOT NULL AFTER product_name";
    
    if (mysqli_query($conn, $alter_table)) {
        echo "Column 'product_name_np' added successfully!<br>";
    } else {
        echo "Error adding 'product_name_np' column: " . mysqli_error($conn) . "<br>";
    }
} else {
    echo "Column 'product_name_np' already exists in 'products' table.<br>";
}

// Check if 'product_description_np' column exists
$check_description_np = "SHOW COLUMNS FROM products LIKE 'product_description_np'";
$result_description_np = mysqli_query($conn, $check_description_np);

if (mysqli_num_rows($result_description_np) == 0) {
    $alter_description_np = "ALTER TABLE products ADD COLUMN product_description_np TEXT AFTER product_description";
    mysqli_query($conn, $alter_description_np);
}

// Check if 'product_price_np' column exists
$check_price_np = "SHOW COLUMNS FROM products LIKE 'product_price_np'";
$result_price_np = mysqli_query($conn, $check_price_np);

if (mysqli_num_rows($result_price_np) == 0) {
    $alter_price_np = "ALTER TABLE products ADD COLUMN product_price_np VARCHAR(50) NOT NULL AFTER product_price";
    mysqli_query($conn, $alter_price_np);
}

// Create the `orders` table
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    id INT NOT NULL,
    status ENUM('active', 'completed', 'abandoned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id) REFERENCES users(id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql_orders)) { 
    echo "Table 'orders' created successfully.<br>";
} else {
    echo "Error creating 'orders' table: " . mysqli_error($conn) . "<br>";
}

// Create the `cart_items` table
$sql_cart_items = "CREATE TABLE IF NOT EXISTS cart_items (
    cart_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10, 2) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql_cart_items)) {
    echo "Table 'cart_items' created successfully.<br>";
} else {
    echo "Error creating 'cart_items' table: " . mysqli_error($conn) . "<br>";
}

$result = mysqli_query($conn, "SHOW COLUMNS FROM cart_items LIKE 'status'");
if (mysqli_num_rows($result) == 0) {
    // Add the column if it doesn't exist
    $alterQuery = "ALTER TABLE cart_items ADD COLUMN status VARCHAR(255) DEFAULT 'active'";
    mysqli_query($conn, $alterQuery);
}

// Create the `order_items` table (new addition)
$sql_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
)";
if (mysqli_query($conn, $sql_order_items)) {
    echo "Table 'order_items' created successfully.<br>";
} else {
    echo "Error creating 'order_items' table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>
