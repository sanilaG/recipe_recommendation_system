<?php
// Database connection
$connection = new mysqli("localhost", "root", "", "recipe_recommendation");

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get the search term from the query parameter
$query = $_GET['query'] ?? '';
$searchTerm = "%" . $connection->real_escape_string($query) . "%"; // Escape special characters

// Prepare the SQL query
$sql = "SELECT r.id, r.recipe_name AS title, COALESCE(AVG(rt.rating), 0) AS ratings, COUNT(rt.id) AS reviews
        FROM recipes r
        LEFT JOIN rating rt ON r.id = rt.recipe_id
        WHERE r.recipe_name LIKE ? OR r.ingredients LIKE ?
        GROUP BY r.id";

$stmt = $connection->prepare($sql);
if (!$stmt) {
    die("Preparation failed: " . $connection->error);
}

// Bind parameters
$stmt->bind_param("ss", $searchTerm, $searchTerm);

// Execute the statement
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $recipes = $result->fetch_all(MYSQLI_ASSOC);
    
    // Return the JSON-encoded results
    echo json_encode($recipes);
} else {
    echo json_encode([]);
}

// Close statement and connection
$stmt->close();
$connection->close();
?>
