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

// Fetch categories from the database
$sql = "SELECT id, category_name FROM categories"; // Replace 'categories' with your actual table name
$result = $conn->query($sql);

// Check if categories exist
$categories = [];
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
} else {
    echo "No categories found.";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file here -->
    <style>
        /* Basic styles for the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
        .add-category {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Categories</h1>
        
        <!-- Link to add a new category -->
        <div class="add-category">
            <a href="add_category.php">Add New Category</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category['id']; ?></td>
                    <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                    <td>
                        <a href="edit_category.php?id=<?php echo $category['id']; ?>">Edit</a> | 
                        <a href="delete_category.php?id=<?php echo $category['id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
