<?php
include '../includes/db.php'; // Ensure this connects to your database

// Check if recipe_id is provided
if (isset($_GET['recipe_id'])) {
    $recipe_id = intval($_GET['recipe_id']);

    // Fetch the recipe details
    $query = "SELECT * FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $recipe_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if recipe was found
    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
    } else {
        echo "Recipe not found.";
        exit;
    }
} else {
    echo "Invalid recipe.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['recipe_name']); ?> - Recipe Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .recipe-details-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .recipe-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .recipe-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .recipe-description, .recipe-ingredients, .recipe-instructions {
            margin-top: 20px;
        }

        .recipe-ingredients ul {
            padding-left: 20px;
        }

        .rating, .time {
            margin-top: 15px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="recipe-details-container">
        <h1 class="recipe-name"><?php echo htmlspecialchars($recipe['recipe_name']); ?></h1>
        <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['recipe_name']); ?>" class="recipe-image">
        
        <p class="recipe-description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
        
        <div class="recipe-ingredients">
            <h3>Ingredients:</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
        </div>
        
        <div class="recipe-instructions">
            <h3>Instructions:</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
        </div>

        <div class="time">
            <strong>Total Time: </strong> <?php echo htmlspecialchars($recipe['total_time']); ?> minutes
        </div>

        <div class="rating">
            <strong>Rating: </strong> <?php echo htmlspecialchars($recipe['average_rating']); ?> ★
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
