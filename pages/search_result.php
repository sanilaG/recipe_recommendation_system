<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=recipe_recommendation', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Get search term from user input
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM recipes 
        WHERE recipe_name LIKE :search 
        OR ingredients LIKE :search 
        OR description LIKE :search";

try {
    // Prepare the statement
    $stmt = $pdo->prepare($sql);
    
    // Bind parameters
    $searchTerm = "%$searchQuery%";
    $stmt->bindParam(':search', $searchTerm);

    // Execute the statement
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display results
    if (empty($results)) {
        echo "<h2>No recipes found for '$searchQuery'.</h2>";
    } else {
        echo "<h2>Search Results for '$searchQuery':</h2>";
        echo "<div class='recipe-results'>";
        foreach ($results as $recipe) {
            echo "<div class='recipe-item'>";
            echo "<h3>{$recipe['recipe_name']}</h3>";
            echo "<img src='{$recipe['image_url']}' alt='{$recipe['recipe_name']}' style='width:200px;height:auto;'>";
            echo "<p>Rating: {$recipe['average_rating']}</p>";
            echo "<p>Reviews: {$recipe['total_reviews']}</p>";
            echo "<a href='../recipe/recipe.php?recipe_id={$recipe['id']}'>View Recipe</a>"; // Link to recipe.php
            echo "</div>";
        }
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>
