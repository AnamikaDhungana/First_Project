<?php
session_start();
date_default_timezone_set('UTC');
require_once "./../database_connection.php";

// Check if user is logged in
$isLoggedIn = isset($_SESSION['isLoggedIn']);

// Handle AJAX requests
if (isset($_GET['action'])) {
    $response = ['success' => false, 'message' => ''];

    switch ($_GET['action']) {
        case 'add_to_cart':
            if (!$isLoggedIn) {
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }

            $product_id = $_GET['product_id'];
            $quantity = $_GET['quantity'];
            $price = $_GET['price'];

            try {
                // Check if user has an active cart
                $stmt = mysqli_prepare($conn, "SELECT order_id FROM orders WHERE id = ? AND status = 'active'");
                mysqli_stmt_bind_param($stmt, "i", $_SESSION['userID']);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $cart = mysqli_fetch_assoc($result);

                if (!$cart) {
                    // Create new cart
                    $stmt = mysqli_prepare($conn, "INSERT INTO orders (id, status, created_at) VALUES (?, 'active', ?)");
                    $date = date('Y-m-d H:i:s');
                    mysqli_stmt_bind_param($stmt, "is", $_SESSION['userID'], $date);
                    mysqli_stmt_execute($stmt);
                    $order_id = mysqli_insert_id($conn);
                } else {
                    $order_id = $cart['order_id'];
                }

                // Check if product already exists in cart
                $stmt = mysqli_prepare($conn, "SELECT * FROM cart_items WHERE order_id = ? AND product_id = ?");
                mysqli_stmt_bind_param($stmt, "ii", $order_id, $product_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $existing_item = mysqli_fetch_assoc($result);

                if ($existing_item) {
                    // Update quantity
                    $stmt = mysqli_prepare($conn, "UPDATE cart_items SET quantity = quantity + ? WHERE order_id = ? AND product_id = ?");
                    mysqli_stmt_bind_param($stmt, "iii", $quantity, $order_id, $product_id);
                    mysqli_stmt_execute($stmt);
                } else {
                    // Add new item
                    $stmt = mysqli_prepare($conn, "INSERT INTO cart_items (order_id, product_id, quantity, price, added_at) VALUES (?, ?, ?, ?, ?)");
                    $date = date('Y-m-d H:i:s');
                    mysqli_stmt_bind_param($stmt, "iiiss", $order_id, $product_id, $quantity, $price, $date);
                    mysqli_stmt_execute($stmt);
                }

                $response['success'] = true;
                $response['message'] = 'Item added to cart';
            } catch (Exception $e) {
                $response['message'] = 'Error adding item to cart: ' . $e->getMessage();
            }

            echo json_encode($response);
            exit();
            break;

        case 'update_quantity':
            if (!$isLoggedIn) {
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }

            $cart_item_id = $_GET['cart_item_id'];
            $quantity = $_GET['quantity'];

            try {
                $stmt = mysqli_prepare($conn, "UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
                mysqli_stmt_bind_param($stmt, "ii", $quantity, $cart_item_id);
                mysqli_stmt_execute($stmt);
                $response['success'] = true;
                $response['message'] = 'Quantity updated';
            } catch (Exception $e) {
                $response['message'] = 'Error updating quantity: ' . $e->getMessage();
            }
            echo json_encode($response);
            exit();
            break;

        case 'checkout':
            if (!$isLoggedIn) {
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }

            $cart_id = $_GET['cart_id']; // Changed from cart_item_id to cart_id
            try {
                $stmt = mysqli_prepare($conn, "UPDATE orders SET status = 'completed' WHERE order_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_id);
                mysqli_stmt_execute($stmt);
                $response['success'] = true;
                $response['message'] = 'Purchase completed';
            } catch (Exception $e) {
                $response['message'] = 'Error during checkout: ' . $e->getMessage();
            }
            echo json_encode($response);
            exit();
            break;

        case 'remove_item':
            if (!$isLoggedIn) {
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }

            $cart_item_id = $_GET['cart_item_id'];

            try {
                $stmt = mysqli_prepare($conn, "DELETE FROM cart_items WHERE cart_item_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_item_id);
                mysqli_stmt_execute($stmt);
                $response['success'] = true;
                $response['message'] = 'Item removed from cart';
            } catch (Exception $e) {
                $response['message'] = 'Error removing item: ' . $e->getMessage();
            }
            echo json_encode($response);
            exit(); // Added exit() for consistency
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
        }
        .item-details {
            flex-grow: 1;
        }
        .item-quantity {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cart-summary {
            margin-top: 20px;
            text-align: right;
        }
        .checkout-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <!--JS Header -->
    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.php') 
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>
    <main class="cart-container">
        <h1>Your Cart</h1>
        <?php if (!$isLoggedIn): ?>
            <div class="login-message">
                Please <a href="../Login/login.php">login</a> to view your cart
            </div>
        <?php else: ?>
            <div id="cart-items">
                <?php
                // Initialize total variable
                $total = 0;
                $cart_id = 0; // Initialize cart_id variable
                
                try {
                    $stmt = mysqli_prepare($conn, "
                        SELECT ci.*, p.product_name, p.image_url, p.product_price, o.order_id
                        FROM cart_items ci
                        JOIN orders o ON ci.order_id = o.order_id
                        JOIN products p ON ci.product_id = p.product_id
                        WHERE o.id = ? AND o.status = 'active'
                    ");
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION['userID']);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $cart_items = [];
                    while($row = mysqli_fetch_assoc($result)) {
                        $cart_items[] = $row;
                        // Store the cart_id for checkout
                        $cart_id = $row['order_id'];
                    }
                    
                    if (empty($cart_items)) {
                        echo '<p>Your cart is empty!</p>';
                    } else {
                        foreach ($cart_items as $item) {
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                            ?>
                            <div class="cart-item" data-id="<?php echo $item['cart_item_id']; ?>">
                                <img src="../Admin_Page/uploads/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                                <div class="item-details">
                                    <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                                    <p>Price: Rs.<?php echo number_format($item['product_price'], 2); ?></p>
                                    <div class="item-quantity">
                                        <label>Quantity:</label>
                                        <input type="number" value="<?php echo $item['quantity']; ?>" min="1" 
                                               onchange="updateQuantity(<?php echo $item['cart_item_id']; ?>, this.value)">
                                    </div>
                                    <p>Subtotal: Rs.<?php echo number_format($subtotal, 2); ?></p>
                                </div>
                                <button onclick="removeItem(<?php echo $item['cart_item_id']; ?>)">Remove</button>
                            </div>
                            <?php
                        }
                    }
                } catch(Exception $e) {
                    echo '<p>Error loading cart items: ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
                ?>
            </div>
            
            <div class="cart-summary">
                <h2>Total: Rs.<?php echo number_format($total, 2); ?></h2>
                <button class="checkout-btn" id="checkout-btn" <?php echo $total == 0 ? 'disabled' : ''; ?> 
                        onclick="checkoutCart(<?php echo $cart_id; ?>)">Proceed to Checkout</button>
            </div>
        <?php endif; ?>
    </main>
</body>
<!--Js Footer -->
<div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>
<script>
      
    // Update item quantity
    function updateQuantity(cart_item_id, quantity) {
        if (quantity <= 0) return;
        const url = add_to_cart.php?action=update_quantity&cart_item_id=${cart_item_id}&quantity=${quantity};
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Refresh the page after update
                } else {
                    alert(data.message);
                }
            });
    }

    // Remove item from cart
    function removeItem(cart_item_id) {
        if (confirm('Are you sure you want to remove this item?')) {
            const url = add_to_cart.php?action=remove_item&cart_item_id=${cart_item_id};
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Refresh the page after removal
                    } else {
                        alert(data.message);
                    }
                });
        }
    }

 // Proceed to checkout
function checkoutCart(cart_id) {
    const url = add_to_cart.php?action=checkout&cart_id=${cart_id};
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Optionally, you can display a success message instead of redirecting to another page
                document.querySelector('.cart-summary').innerHTML = '<h2>Thank you for your order!</h2>';
            } else {
                alert(data.message);
            }
        });
}

</script>

</html>