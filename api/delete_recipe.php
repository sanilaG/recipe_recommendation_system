<?php
header('Content-Type: application/json');
include '../includes/db.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    $query = "DELETE FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Recipe deleted successfully!"]);
    } else {
        echo json_encode(["error" => "Error: " . $stmt->error]);
    }
} else {
    echo json_encode(["error" => "Invalid ID"]);
}
?>
