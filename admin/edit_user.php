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

    // Fetch user data from the database
    $query = "SELECT id, email, profile_picture FROM users WHERE id = $userId";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        echo "User not found.";
        exit;
    }

    $user = $result->fetch_assoc();
} else {
    echo "Invalid request.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $profilePicture = $_FILES['profile_picture'];

    // Handle file upload
    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        $uploadFile = $uploadDir . basename($profilePicture['name']);
        
        // Move uploaded file to the designated directory
        if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
            $profilePictureName = $profilePicture['name'];
        } else {
            $profilePictureName = $user['profile_picture']; // Keep old picture if upload fails
        }
    } else {
        $profilePictureName = $user['profile_picture']; // Keep old picture if no new upload
    }

    // Update user data in the database
    $updateQuery = "UPDATE users SET email = ?, profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssi', $email, $profilePictureName, $userId);

    if ($stmt->execute()) {
        $_SESSION['user_updated'] = true; // Set session variable to indicate success
        header("Location: manage_users.php");
        exit;
    } else {
        echo "Error updating user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input[type="email"], input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .profile-pic {
            width: 100px; /* Set the desired width for the profile picture */
            height: 100px; /* Set the desired height for the profile picture */
            border-radius: 50%; /* Make the image circular */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit User</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture">

            <img src="../uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Current Profile Picture" class="profile-pic">
            
            <input type="submit" value="Update User">
        </form>
    </div>

    <?php
    // Check if the user_updated session variable is set
    if (isset($_SESSION['user_updated']) && $_SESSION['user_updated'] === true) {
        echo "<script>alert('User updated successfully!');</script>";
        unset($_SESSION['user_updated']); // Unset the session variable after displaying the message
    }
    ?>
</body>
</html>
