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
                    // Update quantity if the item already exists
                    $stmt = mysqli_prepare($conn, "UPDATE cart_items SET quantity = quantity + ? WHERE order_id = ? AND product_id = ?");
                    mysqli_stmt_bind_param($stmt, "iii", $quantity, $order_id, $product_id);
                    mysqli_stmt_execute($stmt);
                } else {
                    // Add new item to the cart
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
                $response['success'] = false;
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }
        
            $cart_item_id = $_GET['cart_item_id'];
            $quantity = $_GET['quantity'];
        
            try {
                // Update the quantity in cart_items table
                $stmt = mysqli_prepare($conn, "UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
                mysqli_stmt_bind_param($stmt, "ii", $quantity, $cart_item_id);
                mysqli_stmt_execute($stmt);
        
                // Fetch the product ID and updated quantity from cart_items
                $stmt = mysqli_prepare($conn, "SELECT product_id, quantity FROM cart_items WHERE cart_item_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_item_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $cartItem = mysqli_fetch_assoc($result);
        
                if ($cartItem) {
                    $product_id = $cartItem['product_id'];
                    $quantity = $cartItem['quantity'];
        
                    // Fetch product price from products table
                    $stmt = mysqli_prepare($conn, "SELECT product_price FROM products WHERE product_id = ?");
                    mysqli_stmt_bind_param($stmt, "i", $product_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $product = mysqli_fetch_assoc($result);
        
                    if ($product) {
                        $price_per_item = $product['product_price'];
                        $total_price = $price_per_item * $quantity; // Calculate total price
        
                        $response['success'] = true;
                        $response['message'] = 'Quantity updated';
                        $response['total_price'] = $total_price;
                        $response['quantity'] = $quantity;
                        $response['cart_item_id'] = $cart_item_id;
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Product not found';
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Cart item not found';
                }
            } catch (Exception $e) {
                $response['success'] = false;
                $response['message'] = 'Error updating quantity: ' . $e->getMessage();
            }
        
            echo json_encode($response);
            exit();
            
        case 'checkout':
            if (!$isLoggedIn) {
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }
        
            $cart_id = $_GET['cart_id'];
            try {
                // Check if the order is already completed
                $stmt = mysqli_prepare($conn, "SELECT status FROM orders WHERE order_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $order = mysqli_fetch_assoc($result);
        
                if ($order && $order['status'] == 'completed') {
                    $response['message'] = 'This order has already been completed.';
                    echo json_encode($response);
                    exit;
                }
        
                // Mark the order as completed
                $stmt = mysqli_prepare($conn, "UPDATE orders SET status = 'completed' WHERE order_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_id);
                mysqli_stmt_execute($stmt);
        
                // Move cart items to order_items table
                $stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) 
                    SELECT order_id, product_id, quantity, price FROM cart_items WHERE order_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_id);
                mysqli_stmt_execute($stmt);
        
                // Empty the cart_items table
                $stmt = mysqli_prepare($conn, "DELETE FROM cart_items WHERE order_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $cart_id);
                mysqli_stmt_execute($stmt);
        
                // Check if the cart is now empty
                $cart_empty = true; 
        
                $response['success'] = true;
                $response['message'] = 'Your order has been successfully placed! We will contact you soon.';
                $response['cart_empty'] = $cart_empty;
            } catch (Exception $e) {
                $response['message'] = 'Error during checkout: ' . $e->getMessage();
            }
            echo json_encode($response);
            exit;
        

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
                    echo '<p class="empty-cart-message">Your cart is empty!</p>';
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
                                <p>Subtotal: Rs.<span id="subtotal-<?php echo $item['cart_item_id']; ?>"><?php echo number_format($subtotal, 2); ?></span></p>
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
            <h2>Total: Rs.<span id="cart-total"><?php echo number_format($total, 2); ?></span></h2>
            <button class="checkout-btn" id="checkout-btn" <?php echo $total == 0 ? 'disabled' : ''; ?> 
                    onclick="checkoutCart(<?php echo $cart_id; ?>)">Cash On Delivery</button>
        </div>
    <?php endif; ?>
    </main>

    <!-- JS for footer -->
    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script>
       function updateQuantity(cartItemId, quantity) {
    fetch('add_to_cart.php?action=update_quantity&cart_item_id=' + cartItemId + '&quantity=' + quantity)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update the subtotal for this item
                const subtotalElement = document.getElementById('subtotal-' + cartItemId);
                if (subtotalElement) {
                    subtotalElement.textContent = data.total_price.toFixed(2);
                }
                
                // Recalculate and update the cart total
                updateCartTotal();
                
                alert('Quantity updated');
            } else {
                alert(data.message || 'Error updating quantity');
            }
        })
        .catch(error => {
            console.error('Error updating quantity:', error);
            alert('Error updating quantity. Please try again.');
        });
}

function updateCartTotal() {
    // Calculate the new cart total by summing all subtotals
    let total = 0;
    const subtotalElements = document.querySelectorAll('[id^="subtotal-"]');
    
    subtotalElements.forEach(element => {
        total += parseFloat(element.textContent);
    });
    
    // Update the total display
    const cartTotalElement = document.getElementById('cart-total');
    if (cartTotalElement) {
        cartTotalElement.textContent = total.toFixed(2);
    }
    
    // Enable/disable checkout button based on total
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = total <= 0;
    }
}

        function removeItem(cartItemId) {
            fetch('add_to_cart.php?action=remove_item&cart_item_id=' + cartItemId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Item removed');
                        // Remove the item from the DOM
                        const itemElement = document.querySelector(.cart-item[data-id="${cartItemId}"]);
                        if (itemElement) {
                            itemElement.remove();
                            // Update the cart total
                            updateCartTotal();
                            
                            // Check if cart is empty
                            const cartItems = document.querySelectorAll('.cart-item');
                            if (cartItems.length === 0) {
                                document.getElementById('cart-items').innerHTML = '<p class="empty-cart-message">Your cart is empty!</p>';
                            }
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error removing item:', error));
        }

        function checkoutCart(cart_id) {
    const url = add_to_cart.php?action=checkout&cart_id=${cart_id};
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);

                // Display thank-you message
                document.querySelector('.cart-summary').innerHTML = '<h2>Thank you for your order!</h2>';

                // Clear cart items if empty
                if (data.cart_empty) {
                    document.getElementById('cart-items').innerHTML = '<p>Your cart is empty!!</p>';
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error:", error));
}

    </script>
</body>
</html>
