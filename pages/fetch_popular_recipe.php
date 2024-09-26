<?php
$host = 'localhost';
$dbName = 'recipe_recommendation';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch popular recipes based on total ratings and reviews
    $stmt = $pdo->prepare("
        SELECT id, recipe_name, average_rating, total_reviews, image_url 
        FROM recipes 
        WHERE total_reviews > 0 
        ORDER BY average_rating DESC, total_reviews DESC 
        LIMIT 5
    ");
    $stmt->execute();
    $popularRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($popularRecipes);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
