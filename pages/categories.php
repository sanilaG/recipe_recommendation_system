<?php include 'indexheader.php'; ?>
<?php
include '../includes/db.php'; // Ensure this file connects to your database

// Fetch categories
$query = "SELECT * FROM categories ORDER BY id ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:#d2b7b7;
            margin: 0px;
            padding: 20px;
        }
        
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            justify-content: center;
        }

        .category-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 200px;
            transition: transform 0.2s;
        }

        .category-card:hover {
            transform: scale(1.05);
        }

        .category-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        footer {
            margin-top: 190px; /* Add space above footer */
            padding: 20px; /* Optional: Add padding within the footer */
            background-color: #fff; /* Optional: Set background color */
            text-align: center; /* Center align footer text */
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Categories</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="category-card">';
                echo '<a href="categories_list.php?category_id=' . $row['id'] . '" style="text-decoration: none; color: inherit;">';
                echo '<div class="category-name">' . htmlspecialchars($row['category_name']) . '</div>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No categories found.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>

<?php include 'footer.php'; ?>
