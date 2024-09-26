<?php
include 'db_connect.php'; // Make sure this file has your database connection details

// Fetch the most popular recipes based on ratings
$sql = "SELECT title, ratings, reviews FROM recipes ORDER BY ratings DESC LIMIT 5";
$result = mysqli_query($conn, $sql);

$recipes = array();
while ($row = mysqli_fetch_assoc($result)) {
    $recipes[] = $row;
}

// Return recipes as JSON for use on the frontend
header('Content-Type: application/json');
echo json_encode($recipes);
?>
