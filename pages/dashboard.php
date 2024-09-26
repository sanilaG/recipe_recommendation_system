<?php
session_start(); // Start the session

// Database credentials
$host = 'localhost';
$dbName = 'recipe_recommendation';
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Prepare and execute the query to fetch user data
    $stmt = $pdo->prepare("SELECT username, email, profile_picture FROM users WHERE id = :id");
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user data was retrieved
        if ($userData) {
            $username = htmlspecialchars($userData['username']);
            $email = htmlspecialchars($userData['email']);
            $profilePicture = htmlspecialchars($userData['profile_picture']);
        } else {
            echo "User not found.";
        }
    } else {
        echo "Error executing query.";
    }
} else {
    echo "User is not logged in.";
    exit; // Exit if user is not logged in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        nav {
            margin: 20px 0;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 10px;
        }
        .profile-container {
            display: inline-block;
            position: relative;
        }
        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 150px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .profile-container:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $username; ?>!</h1>
    <p>Email: <?php echo $email; ?></p>
    <img src="../users/images/<?php echo $profilePicture; ?>" alt="Profile Picture" style="width: 100px; height: 100px;">

    <nav>
        <ul>
            <li><a href="aboutus.php">About Us</a></li>
            <li class="profile-info">
                <div class="profile-container">
                    <img src="../users/images/<?php echo $profilePicture; ?>" alt="Profile Image" class="profile-image" onclick="toggleDropdown()">
                    <div class="dropdown-content" id="dropdownMenu">
                        <a href="edit_profile.php">Edit Profile</a>
                        <a href="delete_profile.php">Delete Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
    </nav>

    <script>
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.profile-image')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
