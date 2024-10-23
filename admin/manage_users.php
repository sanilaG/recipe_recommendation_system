<?php
session_start();
include '../includes/db.php';

// Check if the logged-in user is an admin
if ($_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Fetch all users from the database
$query = "SELECT id, email, profile_picture FROM users"; // Assuming you want to show profile pictures as well
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        td img {
            width: 50px; /* Set the desired width for the profile picture */
            height: 50px; /* Set the desired height for the profile picture */
            border-radius: 50%; /* Make the image circular */
        }
        .add-user-btn {
            display: block;
            width: 150px;
            margin: 0 auto 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }
        .add-user-btn:hover {
            background-color: #45a049;
        }
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            color: white;
            border-radius: 5px;
            margin-right: 5px;
        }
        .edit-btn {
            background-color: #3498db;
        }
        .delete-btn {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Users</h1>
        <a href="add_user.php" class="add-user-btn">Add New User</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the users and display them in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        
                    echo "<td>
                            <a href='edit_user.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a>
                            <a href='delete_user.php?id=" . $row['id'] . "' class='delete-btn' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
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
