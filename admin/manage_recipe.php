<?php
include '../includes/db.php'; // Ensure this file connects to your database

// Fetch recipes
$query = "SELECT * FROM recipes ORDER BY id ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
    width: 100%; /* Use 100% width */
    max-width: 2500px; /* Set a maximum width */
    margin: 20px auto; /* Center the table */
    border-collapse: collapse;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #009688;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: #009688;
            padding: 5px 10px;
            border: 1px solid #009688;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }
        a:hover {
            background-color: #009688;
            color: white;
        }
        .no-recipes {
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>

<h1>Manage Recipes</h1>

<?php
if ($result->num_rows > 0) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Recipe Name</th><th>Description</th><th>Ingredients</th><th>Instructions</th><th>Image URL</th><th>Total Time</th><th>Actions</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . htmlspecialchars($row['recipe_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
        echo '<td>' . htmlspecialchars($row['ingredients']) . '</td>';
        echo '<td>' . htmlspecialchars($row['instructions']) . '</td>';
        echo '<td>' . htmlspecialchars($row['image_url']) . '</td>';
        echo '<td>' . htmlspecialchars($row['total_time']) . '</td>';
        echo '<td>';
echo '<a href="edit_recipe.php?id=' . $row['id'] . '" style="color: #009688; text-decoration: none; padding: 5px;">Edit</a> | ';
echo '<a href="delete_recipe.php?id=' . $row['id'] . '" style="color: #e74c3c; text-decoration: none; padding: 5px;" onclick="return confirm(\'Are you sure you want to delete this recipe?\')">Delete</a>';
echo '</td>';

        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<p class="no-recipes">No recipes found.</p>';
}

$conn->close();
?>

</body>
</html>
