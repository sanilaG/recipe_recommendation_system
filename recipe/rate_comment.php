<?php
session_start();
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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipeId = $_POST['recipe_id'];
    $userId = $_SESSION['user_id']; // Assuming the user is logged in and their ID is stored in the session
    $rating = (int) $_POST['rating'];
    $comment = $_POST['comment'];

    // Check if the user has already rated the recipe
    $stmt = $pdo->prepare("SELECT id FROM rating WHERE user_id = :user_id AND recipe_id = :recipe_id");
    $stmt->execute([':user_id' => $userId, ':recipe_id' => $recipeId]);
    $existingRating = $stmt->fetch();

    if ($existingRating) {
        // If the user has already rated, update the rating and comment
        $updateStmt = $pdo->prepare("UPDATE rating SET rating = :rating, comment = :comment, created_at = NOW() WHERE user_id = :user_id AND recipe_id = :recipe_id");
        $updateStmt->execute([
            ':rating' => $rating,
            ':comment' => $comment,
            ':user_id' => $userId,
            ':recipe_id' => $recipeId
        ]);
    } else {
        // Insert the new rating and comment
        $insertStmt = $pdo->prepare("INSERT INTO rating (user_id, recipe_id, rating, comment, created_at) VALUES (:user_id, :recipe_id, :rating, :comment, NOW())");
        $insertStmt->execute([
            ':user_id' => $userId,
            ':recipe_id' => $recipeId,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }

    // Update the recipe's rating statistics
    $ratingStatsStmt = $pdo->prepare("SELECT COUNT(rating) as total_ratings, AVG(rating) as average_rating FROM rating WHERE recipe_id = :recipe_id");
    $ratingStatsStmt->execute([':recipe_id' => $recipeId]);
    $ratingStats = $ratingStatsStmt->fetch(PDO::FETCH_ASSOC);

    $totalRatings = $ratingStats['total_ratings'];
    $averageRating = $ratingStats['average_rating'];

    // Update the recipe table with the new rating statistics
    $updateRecipeStmt = $pdo->prepare("UPDATE recipes SET total_ratings = :total_ratings, average_rating = :average_rating WHERE id = :recipe_id");
    $updateRecipeStmt->execute([
        ':total_ratings' => $totalRatings,
        ':average_rating' => $averageRating,
        ':recipe_id' => $recipeId
    ]);

    // Redirect back to the recipe page
    header("Location: recipe.php?recipe_id=$recipeId");
    exit;
} else {
    die("Invalid request method.");
}
?>
