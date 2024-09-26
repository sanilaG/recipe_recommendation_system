<?php
session_start();
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT favorite_ingredient FROM user_preferences WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$preference_result = $stmt->get_result();
$user_preference = $preference_result->fetch_assoc()['favorite_ingredient'];

$recommendation_query = "SELECT * FROM recipes WHERE ingredients LIKE ?";
$preference = "%" . $user_preference . "%";
$recommend_stmt = $conn->prepare($recommendation_query);
$recommend_stmt->bind_param("s", $preference);
$recommend_stmt->execute();
$recommend_result = $recommend_stmt->get_result();

echo "<h1>Recommended Recipes Based on Your Preferences</h1>";
while ($row = $recommend_result->fetch_assoc()) {
    echo "<div>";
    echo "<h2>" . $row['name'] . "</h2>";
    echo "<p>Ingredients: " . $row['ingredients'] . "</p>";
    echo "<p>Category: " . $row['category'] . "</p>";
    echo "</div>";
}
?>

<a href="recipes.php">Back to All Recipes</a>
