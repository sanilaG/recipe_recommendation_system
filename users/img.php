<?php
include '../includes/db.php'; // Ensure this file connects to your database

// Check if category_id is set in the URL
$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

// Fetch recipes for the selected category
$query = "SELECT * FROM recipes WHERE category_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        
        .recipe-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 10px 0;
            display: flex;
            align-items: center;
        }

        .recipe-image {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            margin-right: 20px;
            object-fit: cover;
        }

        .recipe-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .recipe-description {
            color: #666;
            margin: 5px 0;
        }

        .recipe-rating {
            color: #FFD700; /* Gold color for ratings */
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Recipes</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="recipe-card">';
                echo '<img src="' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['recipe_name']) . '" class="recipe-image">';
                echo '<div>';
                echo '<div class="recipe-name">' . htmlspecialchars($row['recipe_name']) . '</div>';
                echo '<div class="recipe-description">' . htmlspecialchars($row['description']) . '</div>';
                echo '<div class="recipe-rating">Rating: ' . htmlspecialchars($row['rating']) . '/5</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No recipes found for this category.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
