<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proceed to Checkout</title>
    <link rel="stylesheet" href="proceed.css">  
  
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

    <div class="container">
        <h1>Proceed to Checkout</h1>
    <p>Scan the QR code below to make the payment:</p>
    <img src="QR-Code.jpg" alt="e-Sewa QR Code" style="width: 300px; height: 300px;">
    
</div>
<div class="box-cont">
   
    <form action="process_payment.php" method="POST">
        <input type="hidden" name="order_id" value="12345"> <!-- Pass order details -->
        
    </form>
    </div>

    <!-- Footer -->
  <div id="footer-container"></div>
  <script>
    fetch("../Footer/footer.php")
      .then(response => response.text())
      .then(data => {
        document.getElementById('footer-container').innerHTML = data;
      });
  </script>

</div>
</body>
</html>