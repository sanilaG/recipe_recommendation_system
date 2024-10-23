<?php
session_start();
include '../includes/db.php';

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Check if the user ID is set in the query string
if (isset($_GET['id'])) {
    $userId = intval($_GET['id']); // Convert to integer to prevent SQL injection

    // Delete the user from the database
    $deleteQuery = "DELETE FROM users WHERE id = $userId";

    if ($conn->query($deleteQuery) === TRUE) {
        // Set a session variable to indicate success
        $_SESSION['user_deleted'] = true;
    } else {
        // Handle error
        $_SESSION['user_deleted'] = false;
    }
}

// Redirect back to manage_user.php
header("Location: manage_user.php");
exit;
?>
