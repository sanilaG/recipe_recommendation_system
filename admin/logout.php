<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// If you want to destroy the session completely, also delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finally, destroy the session
session_destroy();

// Redirect to login page or home page after logout
header("Location: login.php"); // Change this to your login page
exit();
?>
