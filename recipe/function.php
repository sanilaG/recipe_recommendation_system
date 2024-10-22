<?php
// Get hybrid recommendations with additional popular and all recipes at the end
function getHybridRecommendations($userId, $currentRecipeId, $pdo) {
    // Content-based recommendations
    $contentBasedRecommendations = getContentBasedRecommendations($currentRecipeId, $pdo);
    
    // Collaborative filtering recommendations
    $collaborativeRecommendations = getCollaborativeRecommendations($userId, $pdo);
    
    // Popular recipes (top 10)
    $popularRecipes = getMostPopularRecipes($pdo);
    
    // All remaining recipes
    $allRecipes = getAllRecipes($pdo);
    
    // Combine recommendations serially: Content-based, Collaborative, Popular, then All Recipes
    $combinedRecommendations = array_merge(
        $contentBasedRecommendations,
        $collaborativeRecommendations,
        $popularRecipes,
        array_column($allRecipes, 'id') // Extract recipe IDs from all recipes
    );
    
    // Remove duplicates and maintain the order
    return array_unique($combinedRecommendations);
}

// Content-based recommendation logic
function getContentBasedRecommendations($recipeId, $pdo) {
    // Example query: recommend similar recipes based on category
    $stmt = $pdo->prepare("SELECT id FROM recipes WHERE category_id = (SELECT category_id FROM recipes WHERE id = :recipeId) AND id != :recipeId");
    $stmt->bindParam(':recipeId', $recipeId);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Collaborative filtering logic
function getCollaborativeRecommendations($userId, $pdo) {
    // Example query: recommend recipes rated by users who liked the same recipes as the current user
    $stmt = $pdo->prepare("
        SELECT r.id FROM rating rt
        JOIN rating rt2 ON rt.recipe_id = rt2.recipe_id
        JOIN recipes r ON rt2.recipe_id = r.id
        WHERE rt.user_id = :userId AND rt2.user_id != :userId
        GROUP BY r.id
    ");
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get the 10 most popular recipes (based on rating count or average rating)
function getMostPopularRecipes($pdo) {
    // Example query: Get the top 10 recipes based on the highest number of ratings or average rating
    $stmt = $pdo->query("
        SELECT r.id 
        FROM recipes r
        JOIN rating rt ON r.id = rt.recipe_id
        GROUP BY r.id
        ORDER BY COUNT(rt.id) DESC
        LIMIT 10
    ");
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch all recipes (optional, for displaying all remaining recipes)
function getAllRecipes($pdo) {
    $stmt = $pdo->query("SELECT * FROM recipes");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
