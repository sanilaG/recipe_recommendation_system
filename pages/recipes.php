<?php include 'indexheader.php'; ?>
<?php
// Database configuration
$servername = "localhost"; // Your server name
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "recipe_recommendation"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Updated SQL query to select all recipes
$sql = "SELECT id, recipe_name, total_time, ingredients, ratings, image_url, total_ratings, average_rating FROM recipes";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Recommendation System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f0f5;
        margin: 0;
        padding: 20px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    .recipes-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 20px; /* Space between cards */
        max-width: 1200px;
        margin: 0 auto; /* Center container */
    }

    .recipe-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s; /* Add transition for hover effect */
        width: calc(33.333% - 20px); /* 3 cards per row */
        text-align: center;
    }

    .recipe-card:hover {
        transform: translateY(-5px); /* Lift effect on hover */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .recipe-image {
        width: 100%; /* Responsive image */
        height: 200px;
        object-fit: cover; /* Maintain aspect ratio */
        border-bottom: 3px solid #4CAF50; /* Bottom border */
    }

    .recipe-title {
        font-size: 1.5em;
        color: #333;
        margin: 15px 0;
    }

    .star-rating {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 10px 0;
    }

    .star {
        color: gold;
        font-size: 24px; /* Adjust size as needed */
        margin-right: 2px; /* Space between stars */
    }

    .star.empty {
        color: lightgray; /* Color for empty stars */
    }

    .ratings-count {
        color: #666; /* Color for ratings count */
        font-size: 0.9em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .recipe-card {
            width: calc(50% - 20px); /* 2 cards per row on smaller screens */
        }
    }

    @media (max-width: 480px) {
        .recipe-card {
            width: 100%; /* 1 card per row on very small screens */
        }
    }
</style>

</head>
<body>
    <h2>All Recipes</h2>
    <div class="recipes-container">
        <?php
        // Check if there are results
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="recipe-card">';
                echo '<img src="' . htmlspecialchars($row["image_url"]) . '" alt="' . htmlspecialchars($row["recipe_name"]) . '" class="recipe-image">';
                echo '<h2 class="recipe-title">' . htmlspecialchars($row["recipe_name"]) . '</h2>';
                
                // Display star ratings
                $rating = round($row["average_rating"]); // Assuming average_rating is out of 5
                echo '<div class="star-rating">';
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo '<span class="star">★</span>'; // Filled star
                    } else {
                        echo '<span class="star empty">☆</span>'; // Empty star
                    }
                }
                echo ' (' . htmlspecialchars($row["total_ratings"]) . ' ratings)';
                echo '</div>'; // End star rating
                echo '</div>'; // End recipe card
            }
        } else {
            echo "<p>No recipes found.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>
</body>
</html>
