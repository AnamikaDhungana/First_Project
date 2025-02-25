<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Cart</title>
  <link rel="stylesheet" href="cart.css">
</head>
<body>
  <!-- Header -->
  <div id="header-placeholder"></div>
  <script>
    fetch('../Header/header.php')
      .then(response => response.text())
      .then(data => {
        document.getElementById('header-placeholder').innerHTML = data;
      })
      .catch(error => console.error('Error loading header:', error));
  </script>

  <!-- Main Cart Container -->
  <main class="cart-container">
    <h1>Your Cart</h1>
    <div id="cart-items"></div>
    <div class="cart-summary">
      <p>Total: Rs.<span id="total-price"></span></p>
      <a href="../Login/login.php"></a>
      <a href="../Login/register.php">
        <button class="checkout-btn" id="checkout-btn">Proceed to Checkout</button>
      </a>
  
    </div>
  </main>

  <!-- JavaScript -->
  <script>
    // Function to render cart items
    function renderCart() {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const cartItemsContainer = document.getElementById('cart-items');
      const totalPriceElement = document.getElementById('total-price');
      let totalPrice = 0;

      cartItemsContainer.innerHTML = ''; // Clear cart items container

      if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p>Your cart is empty!</p>';
        totalPriceElement.textContent = '0.00';
        return;
      }

      cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        totalPrice += itemTotal;

        const itemHTML = `
          <div class="cart-item">
            <img src="${item.image}" alt="${item.name}">
            <div class="item-details">
              <span class="item-name">${item.name}</span>
              <span class="item-price">Rs.${item.price}</span>
              <div class="item-quantity">
                <label>Quantity:</label>
                <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)">
              </div>
            </div>
            <span class="item-total">Rs.${itemTotal.toFixed(2)}</span>
            <button class="remove-btn" onclick="removeFromCart(${index})">Remove</button>
          </div>
        `;
        cartItemsContainer.innerHTML += itemHTML;
      });

      totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    // Update item quantity
    function updateQuantity(index, quantity) {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      if (cart[index]) {
        cart[index].quantity = parseInt(quantity);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
      }
    }

    // Remove item from cart
    function removeFromCart(index) {
      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      cart.splice(index, 1); // Remove item at index
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    // Initial render
    renderCart();
  </script>

  <!-- Footer -->
  <div id="footer-container"></div>
  <script>
    fetch("../Footer/footer.php")
      .then(response => response.text())
      .then(data => {
        document.getElementById('footer-container').innerHTML = data;
      });
  </script>
  
  <!-- <script src="./../language/script.js"></script> -->

</body>
</html>