<?php
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEPALI SWADH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../Header/header.css">
    <link rel="preload" href="../language/language.en.js" as="script">
    <link rel="preload" href="../language/language.np.js" as="script"> 
   
</head>

<body>
    <header>
        <div class="top-banner">
            <div class="social-media-icons">
                <a href="https://www.facebook.com/profile.php?id=61573363090867" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://www.instagram.com/_swadhnepali_/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.youtube.com/@NepaliSwadh" target="_blank"><i class="fab fa-youtube"></i></a>
            </div>

            <p class="tagline" id="message">A Cupful of Joy</p>

            <div class="nav-icons">
                <a href="#"><i id="language" class="fas fa-language"></i></a>
                <a href="../Login/login.php"><i class="fas fa-user"></i></a>
                <a href="../Header/add_to_cart.php"><i class="fas fa-shopping-cart"></i></a>
            </div>
        </div>

        <!-- Navigation bar -->
        <nav class="navbar">
            <div class="logo">
                <a href="home_page.php">
                    <img src="../web_image/Layer_1.png" alt="Tea and Accessory Logo">
                </a>
               <span id="title">NEPALI SWADH</span>
            </div>

            <ul class="nav-links">
                <li><a id="home" href="../Header/home_page.php">HOME</a></li>
                <li><a id="products" href="../Header/products.php">TEAS</a></li>
                <li><a id="accessories" href="../tea_set/accessory.php">TEAPOT SET & ACCESSORIES</a></li>
                <li><a id="about_us_title" href="../About_Us/about-us.php">ABOUT US</a></li>
               
                <li><a id="log_out" href="logout.php">LOGOUT</a></li>
            </ul>
        </nav>
    </header>
     
    <script src="./../language/script.js"> </script>

</body>
</html>