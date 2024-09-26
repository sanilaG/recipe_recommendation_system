<?php
header('Content-Type: application/json');
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
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
    if (empty($id) || empty($name) || empty($ingredients) || empty($instructions) || empty($category)) {
        echo json_encode(["error" => "All fields are required!"]);
        exit;
    }

    // Update recipe in database
    $query = "UPDATE recipes SET name = ?, ingredients = ?, instructions = ?, category = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $name, $ingredients, $instructions, $category, $image, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Recipe updated successfully!"]);
    } else {
        echo json_encode(["error" => "Error: " . $stmt->error]);
    }
}
?>
