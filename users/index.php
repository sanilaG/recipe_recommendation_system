<?php include 'indexheader.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Sharing Platform</title>
    <style>
        /* Add the slider CSS here */
        .slider {
            position: relative;
            max-width: 100%;
            overflow: hidden;
            margin: 20px; /* Adjusted margin for both sliders */
        }

        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease;
            width: 100%; /* Ensures the slider takes the full width */
        }

        .slider-image {
            min-width: 100%; /* Each image takes the full width of the slider */
            height: 500px; /* Set a fixed height for the images */
            object-fit: cover; /* Ensures the images cover the entire area without distortion */
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

        .recipe-item {
            display: inline-block;
            margin: 10px;
            text-align: center;
        }

        .recipe-item img {
            width: 200px;
            height: auto;
        }
    </style>
</head>
<body>

<!-- Image Slider -->
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
<div class="slider" id="popular-recipes-slider">
    <div class="slider-wrapper" id="popular-recipes-wrapper"></div>
    <button class="prev" onclick="movePopularSlide(-1)">&#10094;</button>
    <button class="next" onclick="movePopularSlide(1)">&#10095;</button>
</div>

<!-- Add your script here -->
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

    setInterval(() => moveSlide(1), 5000);

    // Popular recipes slider functionality
    let currentPopularSlide = 0;

    function movePopularSlide(direction) {
        const popularSlides = document.querySelectorAll('.recipe-item');
        const totalPopularSlides = popularSlides.length;

        currentPopularSlide += direction;

        if (currentPopularSlide >= totalPopularSlides) {
            currentPopularSlide = 0;
        } else if (currentPopularSlide < 0) {
            currentPopularSlide = totalPopularSlides - 1;
        }

        const popularSliderWrapper = document.getElementById('popular-recipes-wrapper');
        popularSliderWrapper.style.transform = `translateX(-${currentPopularSlide * 100}%)`;
    }

    // Fetch popular recipes when the page loads
    fetch('../pages/fetch_popular_recipe.php')
        .then(response => response.json())
        .then(data => {
            const recipesWrapper = document.getElementById('popular-recipes-wrapper');
            if (data.length === 0) {
                recipesWrapper.innerHTML = '<p>No popular recipes found.</p>';
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
                recipesWrapper.appendChild(recipeItem);
            });
        })
        .catch(error => console.error('Error fetching popular recipes:', error));
</script>

</body>
</html>

<?php include 'footer.php'; ?>
