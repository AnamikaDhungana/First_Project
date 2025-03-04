<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Nepali Swad</title>
    <link rel="stylesheet" href="aboutUs.css">

</head>

<body class="body_class">
    <div id="header-placeholder"></div>
    <script>
        fetch('../Header/header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-placeholder').innerHTML = data;
            })
            .catch(error => console.error('Error loading header:', error));
    </script>

    <!-- Full-Screen Background Section -->
    <section class="about-us">
        <div class="content">
            <h1 id="about_us_title_1">About Us</h1>
            <p class="p_class" id="about_us_description">
                Welcome to <strong>Nepali Swad</strong>, we are on a journey to bring the rich heritage and unique
                flavors of Nepali teas to the world. Our teas reflect the essence of Nepal’s natural beauty, cultural
                richness, and sustainable agricultural practices.
            </p>
        </div>
    </section>

    <!-- Section with Image and Text -->
    <section id="mission">
        <div class="section-content">
            <div class="image">
                <img src="../web_image/tea-farm.png" alt="Our Mission">
            </div>
            <div class="text">
                <h2 id="mission_title">Our Mission</h2>
                <ul>
                    <li id="mission_item_1">Deliver premium-quality tea leaves and accessories</li>
                    <li id="mission_item_2">Support sustainable farming practices</li>
                    <li id="mission_item_3">Offer a seamless online shopping experience globally</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Our Story with Image -->
    <section id="our-story">
        <div class="section-content">

            <div class="text">
                <h2 id="our_story_title">Our Story</h2>
                <p class="p_class" id="our_story_description">
                    Nepali Swad was founded as part of a college project by a group of passionate students at Tribhuvan
                    University:<br><br>
                <ul>
                    <li id="story_member_1">Anamika Dhungana</li>
                    <li id="story_member_2">Amisha Rai</li>
                    <li id="story_member_3">Moti Tamang</li>
                </ul>
                </p>
            </div>
            <div class="image">
                <img src="../web_image/story-image.png" alt="Our Story">
            </div>
        </div>
    </section>

    <div id="footer-container"></div>
    <script>
        fetch("../Footer/footer.php")
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
    </script>

    <script src="./../language/script.js"></script>

</body>

</html>