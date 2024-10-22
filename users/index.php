<?php include 'indexheader.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Sharing Platform</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"> <!-- Google Fonts -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Main Image Slider */
        .slider {
            position: relative;
            max-width: 100%;
            overflow: hidden;
            margin: 20px;
        }

        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease;
            width: 100%;
        }

        .slider-image {
            min-width: 100%;
            height: 500px;
            object-fit: cover;
        }

        button.prev, button.next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            cursor: pointer;
            padding: 10px;
            font-size: 18px;
        }

        button.prev {
            left: 10px;
        }

        button.next {
            right: 10px;
        }

        /* Recipe Grid */
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 items per row */
            gap: 10px; /* Space between items */
            margin: 20px;
        }

        .recipe-item {
            text-align: center;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .recipe-item img {
            width: 100%; /* Full width of the item */
            height: 200px; /* Fixed height for uniformity */
            object-fit: cover; /* Cover to maintain aspect ratio */
        }

        h2 {
            margin: 20px;
        }
    </style>
</head>
<body>

<!-- Main Image Slider -->
<div class="slider">
    <div class="slider-wrapper">
        <img src="../images/home.jpg" alt="Image 1" class="slider-image">
        <img src="../images/home31.jpg" alt="Image 2" class="slider-image">
        <img src="../images/homepage3.png" alt="Image 3" class="slider-image">
        <img src="../images/home4.jpg" alt="Image 4" class="slider-image">
    </div>
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>
</div>

<!-- Most Popular Recipes -->
<h2>Most Popular Recipes</h2>
<div class="recipe-grid" id="popular-recipes-grid"></div>

<script>
    // Main image slider functionality
    let currentSlide = 0;

    function moveSlide(direction) {
        const slides = document.querySelectorAll('.slider-image');
        const totalSlides = slides.length;

        currentSlide += direction;

        if (currentSlide >= totalSlides) {
            currentSlide = 0;
        } else if (currentSlide < 0) {
            currentSlide = totalSlides - 1;
        }

        const sliderWrapper = document.querySelector('.slider-wrapper');
        sliderWrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
    }

    setInterval(() => moveSlide(1), 5000); // Auto-slide every 5 seconds

    // Fetch popular recipes when the page loads
    fetch('../pages/fetch_popular_recipe.php?limit=10') // Adjust the PHP script to limit to 10 recipes
        .then(response => response.json())
        .then(data => {
            const recipesGrid = document.getElementById('popular-recipes-grid');
            if (data.length === 0) {
                recipesGrid.innerHTML = '<p>No popular recipes found.</p>';
                return;
            }
            data.forEach(recipe => {
                const recipeItem = document.createElement('div');
                recipeItem.classList.add('recipe-item');
                
                // Generate star rating
                const totalStars = 5;
                let starsHTML = '';
                const roundedRating = Math.round(recipe.average_rating);
                
                for (let i = 1; i <= totalStars; i++) {
                    starsHTML += i <= roundedRating ? '★' : '☆';
                }

                recipeItem.innerHTML = `
                    <a href="../recipe/recipe.php?recipe_id=${recipe.id}">
                        <img src="${recipe.image_url}" alt="${recipe.recipe_name}">
                        <h3>${recipe.recipe_name}</h3>
                        <p>Rating: ${starsHTML} (${recipe.average_rating})</p>
                        <p>Reviews: ${recipe.total_reviews}</p>
                    </a>
                `;
                recipesGrid.appendChild(recipeItem);
            });
        })
        .catch(error => console.error('Error fetching popular recipes:', error));

</script>

</body>
</html>

<?php include 'footer.php'; ?>
