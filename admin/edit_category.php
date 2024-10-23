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

// Initialize variables
$category_id = $category_name = "";

// Check if an ID is provided
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    $sql = "SELECT category_name FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $category_name = $row['category_name'];
    } else {
        echo "Category not found.";
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];
    $sql = "UPDATE categories SET category_name = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $category_name, $category_id);
    if ($stmt->execute()) {
        header("Location: manage_categories.php"); // Redirect back to manage categories
        exit;
    } else {
        echo "Error updating category.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Category</h1>
        <form method="POST">
            <label for="category_name">Category Name:</label>
            <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_name); ?>" required>
            <br>
            <input type="submit" value="Update Category">
        </form>
        <a href="manage_categories.php">Cancel</a>
    </div>
</body>
</html>
