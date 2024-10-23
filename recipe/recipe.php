<?php 
include 'indexheader.php'; 

// Check if the session has already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start session only if not already started
}

$host = 'localhost';
$dbName = 'recipe_recommendation';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

include 'function.php';

$recipeId = isset($_GET['recipe_id']) ? intval($_GET['recipe_id']) : null;

if ($recipeId) {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->bindParam(':id', $recipeId, PDO::PARAM_INT);
    $stmt->execute();
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe) {
        die("Recipe not found.");
    }

    // Fetch comments for the recipe
    $commentsStmt = $pdo->prepare("SELECT r.comment, r.rating, u.email, r.created_at FROM rating r JOIN users u ON r.user_id = u.id WHERE r.recipe_id = :recipe_id ORDER BY r.created_at DESC");
    $commentsStmt->bindParam(':recipe_id', $recipeId);
    $commentsStmt->execute();
    $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if user is logged in
    $userId = $_SESSION['user_id'] ?? null;

    // Get recipe recommendations
    $recommendations = getHybridRecommendations($userId, $recipeId, $pdo);
    $allRecipes = getAllRecipes($pdo);
} else {
    die("No recipe ID provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['recipe_name']); ?></title>
    <link rel="stylesheet" href="../css/recipe.css">
</head>
<style>
/* Grid container for recommendation items */
.recommendation-grid {
    display: flex;
    flex-direction: row;
    gap: 20px;
    overflow-x: auto;
    padding: 10px;
    white-space: nowrap;
}

/* Each item in the grid */
.recommendation-item {
    flex: 0 0 auto;
    width: 250px;
    text-align: center;
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    display: inline-block;
}

/* Image styling */
.recommendation-item img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

@media (max-width: 768px) {
    .recommendation-item {
        width: 200px;
    }
}

@media (max-width: 480px) {
    .recommendation-item {
        width: 150px;
    }
}
</style>
<body>

<div class="recipe-container">
    <h1><?php echo htmlspecialchars($recipe['recipe_name']); ?></h1>
    
    <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="Recipe Image" style="width: 400px; height: 500px;">
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
    <p><strong>Ingredients:</strong> <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
    <p><strong>Instructions:</strong> <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>

    <h3>Average Rating: 
        <?php 
        $roundedRating = round($recipe['average_rating']);
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $roundedRating ? '★' : '☆';
        }
        ?>
        (<?php echo $recipe['total_ratings']; ?> ratings)
    </h3>

    <?php if ($userId): ?>
        <!-- Display the rating and comment form if the user is logged in -->
        <form method="POST" action="rate_comment.php">
            <input type="hidden" name="recipe_id" value="<?php echo $recipeId; ?>">
            
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required><label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1">★</label>
            </div>

            <label for="comment">Leave a comment:</label>
            <textarea name="comment" id="comment" required></textarea>
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <!-- Display a message prompting the user to log in -->
        <p>Please <a href="../users/login.php">log in</a> to leave a rating or comment.</p>
    <?php endif; ?>

    <h2>User Comments</h2>
    <?php if (count($comments) > 0): ?>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <h4><?php echo htmlspecialchars($comment['email']); ?> 
                    (Rating: 
                    <?php 
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $comment['rating'] ? '★' : '☆';
                    }
                    ?>
                    ) 
                    <small>(<?php echo htmlspecialchars($comment['created_at']); ?>)</small>
                </h4>
                <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No comments yet.</p>
    <?php endif; ?>
</div>

<div class="recommendations">
    <h2>You Might Also Like</h2>
    <div class="recommendation-grid">
        <?php if (!empty($recommendations)): ?>
            <?php foreach ($recommendations as $recId): ?>
                <?php
                $recommendedRecipe = array_filter($allRecipes, fn($r) => $r['id'] == $recId);
                if (!empty($recommendedRecipe)): 
                    $recommendedRecipe = array_shift($recommendedRecipe);
                ?>
                    <div class="recommendation-item">
                        <h3><a href="recipe.php?recipe_id=<?php echo $recommendedRecipe['id']; ?>"><?php echo htmlspecialchars($recommendedRecipe['recipe_name']); ?></a></h3>
                        <img src="<?php echo htmlspecialchars($recommendedRecipe['image_url']); ?>" alt="Recommended Recipe Image" style="width:300px; height:300px;">
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No recommendations available.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
