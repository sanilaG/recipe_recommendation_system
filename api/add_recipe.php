<?php
header('Content-Type: application/json');
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $category = $_POST['category'];

    // Handle file upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = "../uploads/" . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $image = $imageName;
        }
    }

    // Validate input
    if (empty($name) || empty($ingredients) || empty($instructions) || empty($category)) {
        echo json_encode(["error" => "All fields are required!"]);
        exit;
    }

    // Insert recipe into database
    $query = "INSERT INTO recipes (name, ingredients, instructions, category, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $ingredients, $instructions, $category, $image);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Recipe added successfully!"]);
    } else {
        echo json_encode(["error" => "Error: " . $stmt->error]);
    }
}
?>
