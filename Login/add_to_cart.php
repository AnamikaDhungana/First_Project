<?php
session_start(); // Start session for cart management
include("db_connection.php");

// Handle incoming POST request for adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];

    // Initialize cart session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item already exists in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $product_id) {
            $item['quantity'] += $quantity; // Update quantity
            $found = true;
            break;
        }
    }

    // If item is not in cart, add it
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity
        ];
    }

    echo json_encode(['status' => 'success', 'message' => 'Item added to cart']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
