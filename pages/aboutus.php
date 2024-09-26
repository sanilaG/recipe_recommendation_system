<?php include 'indexheader.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Recipe Recommendation System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .content {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .section:nth-child(even) {
            flex-direction: row-reverse; /* Switch order for even sections */
        }
        .section img {
            width: 100%;
            max-width: 300px;
            border-radius: 8px;
            margin: 20px;
        }
    </style>
</head>
<body>

<div class="content">

    <div class="section">
        <img src="images/mission.jpg" alt="Our Mission">
        <div>
            <h2>Our Mission</h2>
            <p>At our Recipe Recommendation System, we aim to bring the joy of cooking to everyone. Our mission is to simplify the process of finding recipes that cater to various tastes, dietary preferences, and occasions.</p>
        </div>
    </div>

    <div class="section">
        <div>
            <h2>What We Offer</h2>
            <p>Our platform features a diverse collection of recipes, ranging from quick meals to gourmet dishes. Each recipe is carefully curated to ensure it meets our quality standards. Users can also share their favorite recipes and rate others, fostering a community of food lovers.</p>
        </div>
        <img src="images/offer.jpg" alt="What We Offer">
    </div>

    <div class="section">
        <img src="images/explore.jpg" alt="Explore and Share">
        <div>
            <h2>Explore and Share</h2>
            <p>Whether you're a novice cook or a seasoned chef, our system provides tailored recommendations based on your preferences. We believe that sharing recipes not only enhances culinary skills but also brings people together around the dining table.</p>
        </div>
    </div>

    <div class="section">
        <div>
            <h2>Join Us</h2>
            <p>We invite you to join our community of culinary enthusiasts. Dive into our extensive recipe collection, contribute your creations, and connect with fellow food lovers. Happy cooking!</p>
        </div>
        <img src="images/join.jpg" alt="Join Us">
    </div>

</div>

</body>
</html>

<?php include 'footer.php'; ?>
