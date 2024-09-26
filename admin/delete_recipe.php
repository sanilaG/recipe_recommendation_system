<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $recipe_id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
    $stmt->bind_param("i", $recipe_id);

    if ($stmt->execute()) {
        echo "Recipe deleted successfully!";
        header("Location: manage_recipes.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "No recipe ID specified.";
}

$conn->close();
?>
