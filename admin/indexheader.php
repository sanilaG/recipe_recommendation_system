<?php session_start(); // Ensure the session is started ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <link rel="stylesheet" href="indexheader.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <div class="wrapper">
            <div class="logo">
                <h1>Cookie</h1>
                <div class="tagline">A Recipe Recommendation System 🥑</div>
            </div>
            <div class="search-container">
                <form action="../pages/search_result.php" method="get">
                    <input type="text" placeholder="Search recipes..." name="search">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div> <!-- End of .wrapper -->
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="../pages/recipes.php">All&nbsp;Recipes</a></li>
                <li><a href="../admin/add_recipe.php">Add recipe</a></li>
                <li><a href="../pages/categories.php">Categories</a></li>
                <li><a href="aboutus.php">About&nbsp;Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php">
                    <li class="profile-info">
                        <div class="profile-container">
                            <img src="recipe_recommender_website/users/images/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Image" class="profile-image" onclick="toggleDropdown()">
                            
                            </div>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("dropdownMenu");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.profile-image')) {
                const dropdowns = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].style.display = "none";
                }
            }
        };
    </script>
</body>
</html>
