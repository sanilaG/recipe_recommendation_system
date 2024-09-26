<?php
// Get hybrid recommendations
function getHybridRecommendations($userId, $currentRecipeId, $pdo) {
    // Content-based recommendations
    $contentBasedRecommendations = getContentBasedRecommendations($currentRecipeId, $pdo);
    
    // Collaborative filtering recommendations
    $collaborativeRecommendations = getCollaborativeRecommendations($userId, $pdo);
    
    // Combine the recommendations
    $combinedRecommendations = array_merge($contentBasedRecommendations, $collaborativeRecommendations);
    
    // Remove duplicates
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

// Fetch all recipes (optional, for displaying recommendations)
function getAllRecipes($pdo) {
    $stmt = $pdo->query("SELECT * FROM recipes");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
