<?php
session_start();
include '../includes/db.php';

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Get the password from the form
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security
    $profilePicture = $_FILES['profile_picture']['name'];
    $uploadDir = 'uploads/'; // Make sure this directory exists and is writable
    $uploadFile = $uploadDir . basename($profilePicture);

    // Move uploaded file to the designated directory
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile)) {
        // Insert new user into the database
        $insertQuery = "INSERT INTO users (email, password, profile_picture) VALUES ('$email', '$hashedPassword', '$uploadFile')";
        
        if ($conn->query($insertQuery) === TRUE) {
            echo "User added successfully!";
            header("Location: manage_user.php"); // Redirect back to manage users
            exit;
        } else {
            echo "Error adding user: " . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <div class="container">
        <h1>Add New User</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" required>

            <button type="submit">Add User</button>
        </form>
    </div>
</body>
</html>
