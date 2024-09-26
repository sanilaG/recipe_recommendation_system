<?php
include '../includes/db.php'; // Ensure this file connects to your database

// Check if category_id is provided
if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);

    // Fetch the category name
    $category_query = "SELECT category_name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($category_query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($category_name);
    
    if ($stmt->fetch() === false) {
        echo "Category not found.";
        exit;
    }
    $stmt->close();

    // Fetch recipes for the selected category
    $query = "SELECT * FROM recipes WHERE category_id = ? ORDER BY recipe_name ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
    } else {
        echo "Error fetching recipes.";
        exit;
    }
} else {
    echo "Invalid category.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes in <?php echo htmlspecialchars($category_name); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .recipe-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: calc(33.333% - 40px); /* 3 cards per row with gap */
            transition: transform 0.2s;
        }

        .recipe-card:hover {
            transform: scale(1.05);
        }

        .recipe-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .recipe-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .recipe-rating {
            margin-top: 10px;
            font-size: 14px;
            color: #ff9900; /* Color for rating */
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Recipes in <?php echo htmlspecialchars($category_name); ?></h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="recipe-card">';
                echo '<a href="../recipe/recipe.php?recipe_id=' . $row['id'] . '" style="text-decoration: none; color: inherit;">';
                echo '<div class="recipe-name">' . htmlspecialchars($row['recipe_name']) . '</div>';
                $imageUrl = !empty($row['image_url']) ? htmlspecialchars($row['image_url']) : 'default_image.jpg';
                echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($row['recipe_name']) . '" class="recipe-image">';
                
                // Check if rating exists
                $rating = !empty($row['rating']) ? htmlspecialchars($row['rating']) : 'N/A'; // Default to 'N/A' if no rating
                echo '<div class="recipe-rating">Rating: ' . $rating . ' ★</div>';
                
                echo '</a>';
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
$stmt->close();
$conn->close();
?>
