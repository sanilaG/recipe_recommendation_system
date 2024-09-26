<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_role = $_SESSION['role']; // Get user role from session

// Check user role and show appropriate content
if ($user_role == 'admin') {
    echo "<h1>Admin Dashboard</h1>";
    // Admin-specific content
} else {
    echo "<h1>User Dashboard</h1>";
    // User-specific content
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recipes</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <!-- Display recipes or other content -->
</body>
</html>
