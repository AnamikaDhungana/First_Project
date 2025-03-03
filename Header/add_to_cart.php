<?php
session_start();
date_default_timezone_set('UTC');

// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'nepaliswadh_db');

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

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
                $stmt = $pdo->prepare("SELECT cart_id FROM cart WHERE user_id = ? AND status = 'active'");
                $stmt->execute([$_SESSION['userID']]);
                $cart = $stmt->fetch();
                
                if (!$cart) {
                    // Create new cart
                    $stmt = $pdo->prepare("INSERT INTO cart (user_id, status, created_at) VALUES (?, 'active', ?)");
                    $stmt->execute([$_SESSION['userID'], date('Y-m-d H:i:s')]);
                    $cart_id = $pdo->lastInsertId();
                } else {
                    $cart_id = $cart['cart_id'];
                }
                
                // Check if product already exists in cart
                $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?");
                $stmt->execute([$cart_id, $product_id]);
                $existing_item = $stmt->fetch();
                
                if ($existing_item) {
                    // Update quantity
                    $stmt = $pdo->prepare("UPDATE cart_items SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?");
                    $stmt->execute([$quantity, $cart_id, $product_id]);
                } else {
                    // Add new item
                    $stmt = $pdo->prepare("INSERT INTO cart_items (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$cart_id, $product_id, $quantity, $price]);
                }
                
                $response['success'] = true;
                $response['message'] = 'Item added to cart';
            } catch(PDOException $e) {
                $response['message'] = 'Error adding item to cart';
            }

            
            // header("Refresh:0");
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
                $stmt = $pdo->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
                $stmt->execute([$quantity, $cart_item_id]);
                $response['success'] = true;
                $response['message'] = 'Quantity updated';
            } catch(PDOException $e) {
                $response['message'] = 'Error updating quantity';
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
              
              $cart_item_id = $_GET['cart_item_id'];
              try {
                $stmt = $pdo->prepare("UPDATE cart SET status = 'completed' WHERE cart_id = ?");
                $stmt->execute([$cart_item_id]);
                  $response['success'] = true;
                  $response['message'] = 'Purchased';
              } catch(PDOException $e) {
                  $response['message'] = 'Error buying '.$e;
              }
    echo json_encode($response);
    return;
    // exit();
              break;
              
        case 'remove_item':
            if (!$isLoggedIn) {
                $response['message'] = 'Please login first';
                echo json_encode($response);
                exit;
            }
            
            $cart_item_id = $_GET['cart_item_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
                $stmt->execute([$cart_item_id]);
                $response['success'] = true;
                $response['message'] = 'Item removed from cart';
            } catch(PDOException $e) {
                $response['message'] = 'Error removing item';
            }
    echo json_encode($response);

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
    <div id="header-placeholder"></div>
    
    <main class="cart-container">
        <h1>Your Cart</h1>
        <?php if (!$isLoggedIn): ?>
            <div class="login-message">
                Please <a href="../Login/login.php">login</a> to view your cart
            </div>
        <?php else: ?>
            <div id="cart-items">
                <?php
                try {
                    $stmt = $pdo->prepare("
                        SELECT ci.*, p.product_name, p.image_url, p.product_price 
                        FROM cart_items ci
                        JOIN cart c ON ci.cart_id = c.cart_id
                        JOIN products p ON ci.product_id = p.product_id
                        WHERE c.user_id = ? AND c.status = 'active'
                    ");
                    $stmt->execute([$_SESSION['userID']]);
                    $cart_items = $stmt->fetchAll();
                    $total = 0;
                    
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
                } catch(PDOException $e) {
                    echo '<p>Error loading cart items</p>';
                }
                ?>
            </div>
            
            <div class="cart-summary">
                <h2>Total: Rs.<?php echo number_format($total, 2); ?></h2>
                <button class="checkout-btn" onclick="checkout(<?php echo $item['cart_id']; ?>)" <?php echo empty($cart_items) ? 'disabled' : ''; ?>>
                    Proceed to Checkout
                </button>
            </div>
        <?php endif; ?>
    </main>

    <div id="footer-placeholder"></div>

    <script>
        // Load header and footer
        fetch('../Header/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            });

        fetch('../Footer/footer.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-placeholder').innerHTML = data;
            });

        // Cart functions
        function updateQuantity(cartItemId, quantity) {
            fetch('add_to_cart.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update_quantity&cart_item_id=${cartItemId}&quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        function removeItem(cartItemId) {
            if (confirm('Are you sure you want to remove this item?')) {
                fetch('add_to_cart.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove_item&cart_item_id=${cartItemId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }
        }

        function checkout(cartItemId) {
          if (confirm('Are you sure you want to buy?')) {

            console.log("id :: " + cartItemId);

                fetch(`add_to_cart.php?action=checkout&cart_item_id=${cartItemId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }})
                .then(response => response.json())
                .then(data => {

                  console.log(data);

                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
            }



        }
    </script>
</body>
</html>