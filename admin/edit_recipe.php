<?php
include '../includes/db.php'; // Ensure this file connects to your database

// Fetch the recipe ID from the URL (assuming it's passed as a query parameter)
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If a POST request is made to update the recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_id = $_POST['recipe_id']; // Get the recipe ID from the hidden input
    $recipe_name = $_POST['recipe_name'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $category_id = $_POST['category_id'];
    $total_time = $_POST['total_time']; // New field for total time
    $ratings = $_POST['ratings']; // New field for ratings
    $review = $_POST['review']; // New field for review

    // Handle file upload (optional)
    $image_path = ''; // Initialize image path
    if (!empty($_FILES['image']['name'])) { // Only handle upload if an image is provided
        $image = $_FILES['image'];
        $upload_dir = 'uploads/'; // Directory where images will be saved
        $image_path = $upload_dir . basename($image['name']);

        // Check if the upload directory exists, if not create it
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move the uploaded file to the uploads directory
        if (!move_uploaded_file($image['tmp_name'], $image_path)) {
            echo 'Error uploading image.';
            exit;
        }
    }

    // Update recipe in the database
    $query = "UPDATE recipes SET recipe_name=?, description=?, ingredients=?, instructions=?, category_id=?, total_time=?, ratings=?, review=?" .
             (!empty($image_path) ? ", image_url=?" : "") . " WHERE id=?";
    $stmt = $conn->prepare($query);

    // Bind parameters
    if (!empty($image_path)) {
        $stmt->bind_param('ssssiiissi', $recipe_name, $description, $ingredients, $instructions, $category_id, $total_time, $ratings, $review, $image_path, $recipe_id);
    } else {
        $stmt->bind_param('ssssiiisi', $recipe_name, $description, $ingredients, $instructions, $category_id, $total_time, $ratings, $review, $recipe_id);
    }

    if ($stmt->execute()) {
        echo 'Recipe updated successfully!';
    } else {
        echo 'Error: ' . $stmt->error;
    }
}

// Fetch the current recipe details for editing
$query = "SELECT * FROM recipes WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $recipe_id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Recipe</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to left bottom, #67b26f, #4ca2cd);
            margin: 0;
            padding: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid #feb47b; /* Colorful border */
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 2.5em;
        }
        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
            color: #ff7e5f; /* Colorful label */
        }
        input[type="text"],
        input[type="file"],
        select,
        textarea,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #feb47b; /* Colorful border */
            border-radius: 8px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="file"]:focus,
        select:focus,
        textarea:focus,
        input[type="number"]:focus {
            border-color: #ff7e5f; /* Change border color on focus */
        }
        input[type="submit"] {
            background-color: #ff7e5f; /* Colorful button */
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            font-size: 1.1em;
        }
        input[type="submit"]:hover {
            background-color: #feb47b; /* Change color on hover */
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        input[type="file"] {
            padding: 5px;
        }
        ::placeholder {
            color: #ccc; /* Placeholder color */
            opacity: 0.8;
        }
        input[type="text"]:hover,
        textarea:hover,
        input[type="number"]:hover {
            border-color: #feb47b; /* Change border color on hover */
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit Recipe</h1>
        <form action="edit_recipe.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="recipe_id" value="<?php echo htmlspecialchars($recipe['id']); ?>">
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" id="recipe_name" name="recipe_name" value="<?php echo htmlspecialchars($recipe['recipe_name']); ?>" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
            
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
            
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="6" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
            
            <label for="total_time">Total Time (in minutes):</label>
            <input type="number" id="total_time" name="total_time" value="<?php echo htmlspecialchars($recipe['total_time']); ?>" required>
            
            <label for="ratings">Ratings:</label>
            <input type="number" id="ratings" name="ratings" value="<?php echo htmlspecialchars($recipe['ratings']); ?>" step="0.1" min="0" max="5" required>
            
            <label for="review">Review:</label>
            <textarea id="review" name="review" rows="4"><?php echo htmlspecialchars($recipe['review']); ?></textarea>
            
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*">
            
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                <?php
                // Query to dynamically generate options from categories table
                $query = "SELECT * FROM categories";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '"' . ($row['id'] == $recipe['category_id'] ? ' selected' : '') . '>' . htmlspecialchars($row['category_name']) . '</option>';
                }
                ?>
            </select>
            <input type="submit" value="Update Recipe">
        </form>
    </div>
</body>
</html>
