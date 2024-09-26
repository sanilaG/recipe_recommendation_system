<?php
include '../includes/db.php';

// Initialize variables
$recipes_count = $categories_count = $users_count = 0;
$recent_recipes = $recent_users = [];

// Function to fetch counts
function fetchCount($conn, $table) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM $table");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['count'];
    } else {
        throw new Exception("Error fetching count from $table: " . $conn->error);
    }
}

// Fetch counts
try {
    $recipes_count = fetchCount($conn, 'recipes');
    $categories_count = fetchCount($conn, 'categories');
    $users_count = fetchCount($conn, 'users');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch recent recipes
try {
    $recent_recipes_query = $conn->query("SELECT * FROM recipes ORDER BY created_at DESC LIMIT 5");
    if ($recent_recipes_query) {
        while ($recipe = $recent_recipes_query->fetch_assoc()) {
            $recent_recipes[] = $recipe;
        }
    } else {
        throw new Exception("Error fetching recent recipes: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Fetch recent user registrations
try {
    $recent_users_query = $conn->query("SELECT email, created_at FROM users ORDER BY created_at DESC LIMIT 5");
    if ($recent_users_query) {
        while ($user = $recent_users_query->fetch_assoc()) {
            $recent_users[] = $user;
        }
    } else {
        throw new Exception("Error fetching recent users: " . $conn->error);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-panel">
        <h1>Admin Dashboard</h1>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
        <div class="dashboard-stats">
            <div class="stat">
                <h2>Total Recipes</h2>
                <p><?php echo $recipes_count; ?></p>
            </div>
            <div class="stat">
                <h2>Total Categories</h2>
                <p><?php echo $categories_count; ?></p>
            </div>
            <div class="stat">
                <h2>Total Users</h2>
                <p><?php echo $users_count; ?></p>
            </div>
        </div>
        
        <div class="quick-links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="add_recipe.php">Add Recipe</a></li>
                <li><a href="manage_recipe.php">Manage Recipe</a></li>
                <li><a href="manage_categories.php">Manage Categories</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
            </ul>
        </div>

        <div class="recent-activity">
            <h2>Recent Recipes</h2>
            <ul>
                <?php foreach ($recent_recipes as $recipe) : ?>
                    <li>
                        <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>">
                            <?php echo htmlspecialchars($recipe['recipe_name']); ?>
                        </a>
                        <span><?php echo date('Y-m-d', strtotime($recipe['created_at'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <h2>Recent User Registrations</h2>
            <ul>
                <?php foreach ($recent_users as $user) : ?>
                    <li>
                        <?php echo htmlspecialchars($user['email']); ?>
                        <span><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

       
    </div>
</body>
</html>
