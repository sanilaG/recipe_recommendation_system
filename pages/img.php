<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recipe_recommendation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch image from the recipes table
$sql = "SELECT image_url FROM recipes WHERE id=1"; // Change the condition as needed
$result = $conn->query($sql);

$imageSrc = ""; // Initialize image source

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $imageSrc = $row["image_url"]; // Assume the image URL is stored in this column
    }
} else {
    echo "No image found.";
}

$conn->close(); // Close the connection
?>

<body>
    <?php if ($imageSrc): ?>
        <img src="<?php echo htmlspecialchars($imageSrc); ?>" width="175" height="200" alt="Recipe Image" />
    <?php else: ?>
        <p>No image to display.</p>
    <?php endif; ?>
</body>
