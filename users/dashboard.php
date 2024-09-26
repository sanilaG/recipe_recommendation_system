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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT email, profile_picture FROM users WHERE id = :id");
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Handle delete account
if (isset($_POST['delete'])) {
    $deleteStmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $deleteStmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $deleteStmt->execute();

    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .profile-image {
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #007bff;
        }
        .profile-info {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 8px;
            background: #f7f9fc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn {
            display: inline-block;
            width: 48%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-edit {
            background: #007bff;
        }
        .btn-edit:hover {
            background: #0056b3;
        }
        .btn-logout, .btn-delete {
            background: #dc3545;
        }
        .btn-logout:hover, .btn-delete:hover {
            background: #c82333;
        }
        .btn:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Dashboard</h1>
        <div class="profile-info">
            <?php
            $imagePath = 'recipe_recommender_website/users/images/' . htmlspecialchars($userData['profile_picture']);
            if (file_exists($imagePath)) {
                echo '<img src="' . $imagePath . '" alt="Profile Image" class="profile-image">';
            } else {
                echo '<img src="path/to/default-image.png" alt="Default Profile Image" class="profile-image">';
            }
            ?>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
        </div>
        
        <form method="GET" action="edit_profile.php">
            <button type="submit" class="btn btn-edit">Edit Profile</button>
        </form>

        <form method="POST">
            <button type="submit" name="logout" class="btn btn-logout">Logout</button>
            <button type="submit" name="delete" class="btn btn-delete">Delete Account</button>
        </form>
    </div>
</body>
</html>
