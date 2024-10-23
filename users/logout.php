<?php
session_start();
session_destroy(); // Destroy the session
header('Location: login.php'); 
header('Location: index.php'); 
// Redirect to login page after logout
exit();
?>
