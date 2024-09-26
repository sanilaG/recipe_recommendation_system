<?php
session_start();
$host = 'localhost';
$dbName = 'recipe_recommendation';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user data
    $stmt = $pdo->prepare("SELECT email, profile_picture FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newEmail = $_POST['email'];
        $newPassword = $_POST['password'];
        $profilePicture = $userData['profile_picture']; // Default to current profile picture

        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
            $fileName = $_FILES['profile_picture']['name'];
            $fileSize = $_FILES['profile_picture']['size'];
            $fileType = $_FILES['profile_picture']['type'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            // Check file type and size
            if (in_array($fileType, $allowedTypes) && $fileSize < 2 * 1024 * 1024) { // 2MB limit
                $uploadFileDir = 'images/'; // Ensure this directory exists
                $newFileName = uniqid() . '_' . $fileName;
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $profilePicture = $newFileName; // Use new profile picture
                } else {
                    echo "Error uploading the file.";
                }
            } else {
                echo "Invalid file type or size exceeds 2MB.";
            }
        }

        // Update email and password if provided
        $updateStmt = $pdo->prepare("UPDATE users SET email = :email, profile_picture = :profile_picture WHERE id = :id");
        $updateStmt->bindParam(':email', $newEmail);
        $updateStmt->bindParam(':profile_picture', $profilePicture);
        $updateStmt->bindParam(':id', $userId);

        // Hash the new password if it is set
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $pdo->prepare("UPDATE users SET email = :email, password = :password, profile_picture = :profile_picture WHERE id = :id");
            $updateStmt->bindParam(':password', $hashedPassword);
            $updateStmt->bindParam(':email', $newEmail);
            $updateStmt->bindParam(':profile_picture', $profilePicture);
            $updateStmt->bindParam(':id', $userId);
        }

        $updateStmt->execute();

        // Set success message
        $_SESSION['update_success'] = true;
        header("Location: edit_profile.php");
        exit();
    }
} else {
    echo "User not logged in.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Optional: Link your CSS file -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .profile-image {
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"],
        input[type="file"] {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            background-color: #007bff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .notification {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1000;
            transition: opacity 0.5s;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <img src="images/<?php echo htmlspecialchars($userData['profile_picture']); ?>" alt="Profile Image" class="profile-image">
        <form method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>

            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" placeholder="Leave blank to keep current password">

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture">

            <button type="submit">Update Profile</button>
        </form>
    </div>

    <!-- Notification Popup -->
    <div class="notification" id="notification">
        Profile updated successfully!
    </div>

    <script>
        // Show notification if set
        window.onload = function() {
            <?php if (isset($_SESSION['update_success'])): ?>
                document.getElementById('notification').style.display = 'block';
                setTimeout(() => {
                    document.getElementById('notification').style.opacity = '1';
                    setTimeout(() => {
                        document.getElementById('notification').style.opacity = '0';
                        setTimeout(() => {
                            document.getElementById('notification').style.display = 'none';
                        }, 500);
                    }, 2000);
                }, 100);
                <?php unset($_SESSION['update_success']); ?>
            <?php endif; ?>
        };
    </script>
</body>
</html>
