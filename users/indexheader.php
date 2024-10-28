<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <link rel="stylesheet" href="indexheader.css">
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
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="../pages/recipes.php">All&nbsp;Recipes</a></li>
                
                <li><a href="../pages/categories.php">Categories</a></li>
                <li><a href="aboutus.php">About&nbsp;Us</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="profile-info">
                        <a href="dashboard.php">
                            <div class="profile-container">
                                <!-- Check if profile picture path is correct -->
                                <img src="images/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Image" class="profile-image" onclick="toggleDropdown()" width="50" height="50">
                            </div>
                        </a>
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
