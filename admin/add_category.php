<?php
session_start();
include '../includes/db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profile_picture = ''; // Initialize profile picture variable

    // Check if a file is uploaded
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
            $profile_picture = $targetFilePath; // Set the path for the profile picture
        } else {
            echo "Error uploading file.";
        }
    }

    // Insert the user into the database
    $insertQuery = "INSERT INTO users (email, password, profile_picture) VALUES ('$email', '$password', '$profile_picture')";

    if ($conn->query($insertQuery) === TRUE) {
        $_SESSION['user_added'] = true; // Set session variable for success
        header("Location: add_user.php"); // Redirect to the same page
        exit;
    } else {
        echo "Error adding user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Your existing styles */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"],
        input[type="file"],
        button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New User</h1>
        <form action="add_user.php" method="POST" enctype="multipart/form-data">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit">Add User</button>
        </form>
    </div>

    <?php
    // Check if the user_added session variable is set
    if (isset($_SESSION['user_added']) && $_SESSION['user_added'] === true) {
        echo "<script>alert('User added successfully!');</script>";
        unset($_SESSION['user_added']); // Unset the session variable after displaying the message
    }
    ?>
</body>
</html>
