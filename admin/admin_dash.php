<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../login_page.php");
    exit();
} else {
    include "../connection/connection.php";
    
    // Count the number of recipes
    $query = "SELECT COUNT(*) AS recipe_count FROM recipes";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $recipeCount = $row['recipe_count'];

    // Count the number of categories
    $query = "SELECT COUNT(*) AS category_count FROM categories";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $categoryCount = $row['category_count'];

    // Count the number of ratings
    $query = "SELECT COUNT(*) AS rating_count FROM ratings";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $ratingCount = $row['rating_count'];

    // Count the number of reviews
    $query = "SELECT COUNT(*) AS review_count FROM reviews";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $reviewCount = $row['review_count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dash.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css'>
</head>
<body>
    <div class="dashboard">
        <section id="sidebar">
            <div class="icon1">
                <a href="../index.php">
                    <img src="admin_img/admin.png" alt="Logo1">
                </a>
            </div>
            <ul class="side-menu top">
                <li>
                    <a href="admin_dash.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="recipes.php">
                        <i class='bx bx-food-menu'></i>
                        <span class="text">Recipes</span>
                    </a>
                </li>
                <li>
                    <a href="categories.php">
                        <i class='bx bx-category'></i>
                        <span class="text">Categories</span>
                    </a>
                </li>
            </ul>
            <ul class="side-menu">
                <li>
                    <a href="../connection/logout.php" class="logout" onclick="return confirm('Are you sure you want to log out?')">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Logout</span>
                    </a>
                </li>
            </ul>
        </section>

        <section class="main">
            <section class="right-upper">
                <div class="right_about">
                    <div>
                        <p><?php echo $_SESSION['email']; ?></p>
                    </div>
                    <div class="profile">
                        <img src="admin_img/admin_avatar.png" alt="Avatar" class="avatar">
                    </div>
                </div>
            </section>

            <section class="right-lower">
                <ul class="box-info">
                    <li>
                        <i class='bx bx-food-menu bx-icon'></i>
                        <span class="text">
                            <h3><?php echo $recipeCount; ?></h3>
                            <p>No. of<br>Recipes</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-category bx-icon'></i>
                        <span class="text">
                            <h3><?php echo $categoryCount; ?></h3>
                            <p>No. of<br>Categories</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-star bx-icon'></i>
                        <span class="text">
                            <h3><?php echo $ratingCount; ?></h3>
                            <p>Total<br>Ratings</p>
                        </span>
                    </li>
                    <li>
                        <i class='bx bx-message-square-detail bx-icon'></i>
                        <span class="text">
                            <h3><?php echo $reviewCount; ?></h3>
                            <p>Total<br>Reviews</p>
                        </span>
                    </li>
                </ul>

                <section class="admin-table">
                    <h2>Recipes List</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Recipe Name</th>
                                <th>Category</th>
                                <th>Created At</th>
                                <th>Action</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query to retrieve all recipes
                            $query = "SELECT recipes.id, recipes.recipe_name, categories.category_name, recipes.created_at 
                                      FROM recipes
                                      JOIN categories ON recipes.category_id = categories.id";
                            $result = mysqli_query($conn, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['recipe_name'] . "</td>";
                                echo "<td>" . $row['category_name'] . "</td>";
                                echo "<td>" . $row['created_at'] . "</td>";
                                
                                // Add buttons for editing and deleting recipes
                                echo "<td><a href='recipe_edit.php?id=" . $row['id'] . "' onclick=\"return confirm('Do you want to edit this recipe?')\"><button class='button-edit'>Edit</button></a></td>";
                                echo "<td><a href='recipe_delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this recipe?')\"><button class='button-delete'>Delete</button></a></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </section>
        </section>
    </div>
    
</body>
</html>
