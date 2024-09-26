<?php
// Database connection
$host = 'localhost';
$db = 'recipe_recommendation';
$user = 'root'; // your username
$pass = ''; // your password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Fetch the recipe ID from the URL
$recipe_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the selected recipe
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
$stmt->execute(['id' => $recipe_id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe) {
    echo "Recipe not found.";
    exit;
}

// Fetch related recipes using collaborative filtering
$stmt = $pdo->prepare("
    SELECT r.* FROM recipes r
    JOIN user_interaction ui ON r.id = ui.recipe_id
    WHERE ui.user_id IN (
        SELECT user_id FROM user_interaction WHERE recipe_id = :recipe_id
    ) AND r.id != :recipe_id
    LIMIT 5
");
$stmt->execute(['recipe_id' => $recipe_id]);
$related_recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($recipe['recipe_name']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($recipe['recipe_name']); ?></h1>
    <p><?php echo htmlspecialchars($recipe['description']); ?></p>
    <h2>Ingredients</h2>
    <p><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
    <h2>Instructions</h2>
    <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
    <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['recipe_name']); ?>">

    <h2>Related Recipes</h2>
    <ul>
        <?php foreach ($related_recipes as $related): ?>
            <li>
                <a href="view_recipe.php?id=<?php echo $related['id']; ?>">
                    <?php echo htmlspecialchars($related['recipe_name']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
