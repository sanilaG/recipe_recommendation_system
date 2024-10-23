<?php
// Database configuration
$host = 'localhost';
$dbName = 'recipe_recommendation';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an ID is provided
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    if ($stmt->execute()) {
        header("Location: manage_categories.php"); // Redirect back to manage categories
        exit;
    } else {
        echo "Error deleting category.";
    }
} else {
    echo "No category ID provided.";
}

$conn->close();
?>
