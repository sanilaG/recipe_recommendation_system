<?php
header('Content-Type: application/json');
include '../includes/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "SELECT * FROM recipes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
        echo json_encode($recipe);
    } else {
        echo json_encode(["error" => "Recipe not found"]);
    }
} else {
    echo json_encode(["error" => "Invalid ID"]);
}
?>
