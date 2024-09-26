<?php
include '../includes/db.php'; // Ensure this file connects to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipe_name = $_POST['recipe_name'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $category_id = $_POST['category_id'];

    // Handle file upload
    $image = $_FILES['image'];
    $upload_dir = 'uploads/'; // Directory where images will be saved
    $image_path = $upload_dir . basename($image['name']);

    // Check if the upload directory exists, if not create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($image['tmp_name'], $image_path)) {
        // Insert recipe into the database
        $query = "INSERT INTO recipes (recipe_name, ingredient, instructions, image_url, category_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssssi', $recipe_name, $description, $ingredients, $instructions, $image_path, $category_id);
        
        if ($stmt->execute()) {
            echo 'Recipe added successfully!';
        } else {
            echo 'Error: ' . $stmt->error;
        }
    } else {
        echo 'Error uploading image.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Recipe</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to left bottom, #67b26f, #4ca2cd);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Prevent overflow of body */
        }

        /* Centered form container */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            height: 80vh; /* Fixed height for scrollable effect */
            overflow-y: auto; /* Enable vertical scrolling */
        }

        /* Form heading */
        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }

        /* Form labels */
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }

        /* Input, textarea, and select elements */
        input[type="text"], textarea, select, input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        /* Input focus styles */
        input[type="text"]:focus, textarea:focus, select:focus, input[type="file"]:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Submit button */
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        /* Submit button hover effect */
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Add a New Recipe</h1>
        <form action="add_recipe.php" method="POST" enctype="multipart/form-data">
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" id="recipe_name" name="recipe_name" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>
            
            <label for="ingredients">Ingredients:</label>
            <textarea id="ingredients" name="ingredients" rows="4" required></textarea>
            
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="6" required></textarea>
            
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>
            
            <label for="category_id">Category:</label>
            <select id="category_id" name="category_id" required>
                <option value="">Select a category</option>
                <?php
                // Query to dynamically generate options from categories table
                $query = "SELECT * FROM categories";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['category_name']) . '</option>';
                }
                ?>
            </select>

            <input type="submit" value="Add Recipe">
        </form>
    </div>
</body>
</html>
