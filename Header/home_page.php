<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEPALI SWADH</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="home_styles.css">
    <link rel="preload" href="../language/language.en.js" as="script">
    <link rel="preload" href="../language/language.np.js" as="script">

    <!--- <link rel="icon" type="image/png" href="Layer_1.png">-->
</head>

<body>

    <!--JS for the Header-->

    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

    <!-- Hero Section with Background Video -->
    <section class="hero">
        <video autoplay loop muted playsinline class="background-video">
            <source src="bg_video.mp4" type="video/mp4">
        </video>
        <div class="hero-content">
            <h1 id="hero_title">Premium Tea Collection</h1>
            <p id="hero_subtitle">Our new range of flower teas are now available</p>
            <a id="explore" href="#featured-products" class="btn">Explore</a>
        </div>
    </section>

    <!-- Why Choose Nepali Swadh Section -->
    <section class="why-choose">
        <h2 id="why_choose">Why Choose Nepali Swadh?</h2>
        <div class="benefits-container">

            <!-- Healthy Benefit -->
            <div class="benefit">
                <i class="fas fa-heartbeat icon"></i>
                <h3 id="healthy">Healthy</h3>
                <p id="healthy_desc">Nourishing, antioxidant-rich, invigorating, natural</p>
            </div>

            <!-- Sustainable Benefit -->
            <div class="benefit">
                <i class="fas fa-seedling icon"></i>
                <h3 id="sustainable">Sustainable</h3>
                <p id="sustainable_desc">Eco-friendly, Sustainable sips</p>
            </div>

            <!-- Sourced from the Himalayas -->
            <div class="benefit">
                <i class="fas fa-mountain icon"></i>
                <h3 id="sourced">Sourced from the Himalayas</h3>
                <p id="sourced_desc">Himalayan-sourced tea with delightful flavor</p>
            </div>
        </div>
    </section>

    <!--Featured Products-->
    <section id="featured-products" class="featured-products">
        <h2 id="featured_products">Featured Products</h2>
        <span class="product-card">
            <img src="../Tea_name/ctc-tea.png" alt="Black Tea"> <br>
            <h3 id="black_tea">Black Tea</h3>
            <p id="black_tea_price">Rs 300 - Rs 1,200</p> <br>
            <!-- <div class="rating">⭐⭐⭐⭐⭐</div> -->
            <a href="../Header/products.php#black-tea">
                <button id="select_option">Select options</button>
            </a>
        </span>

        <span class="product-card">
            <img src="../Tea_name/chamomile-tea.png" alt="Chamomile Tea"> <br>
            <h3 id="flower_tea"> Flower Tea</h3>
            <p id="flower_tea_price">Rs 300 - Rs 600</p> <br>
            <a href="../Header/products.php#flower-tea">
                <button id="select_option1">Select options</button>
            </a>
        </span>

        <span class="product-card">
            <img src="../Tea_name/lemongrass-tea.png" alt="Herbal Tea"><br>
            <h3 id="herbal_tea">Herbal Tea</h3>
            <p id="herbal_tea_price">Rs 800 - Rs 4,500</p> <br>
            <a href="../Header/products.php#herbal-tea">
                <button id="select_option2">Select options</button>
            </a>
        </span>
        <span class="product-card">
            <img src="../Tea_name/silver_needle_tea.png" alt="Everest Gold Pure Tea"><br>
            <h3 id="green_tea">Green Tea</h3>
            <p id="green_tea_price">Rs 250 - Rs 1,900</p> <br>
            <a href="../Header/products.php#green-tea">
                <button id="select_option3">Select options</button>
            </a>
        </span>
    </section>

    <!--Javascript for footer-->
    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script src="./../language/script.js"> </script>
</body>

</html>