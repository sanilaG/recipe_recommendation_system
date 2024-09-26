<?php include 'header.php'; ?>

<?php
// Server-side validation starts here
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    include '../includes/db.php';

    // Fetch form data
    $email = trim($_POST['Email']);
    $password = $_POST['Password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Email already exists.";
        }
    }

    // Validate password
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // Confirm passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // If no errors, insert data into the database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);
        if ($stmt->execute()) {
            echo "User registered successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="center">
        <form action="register.php" method="POST" class="form">
            <h2>Create new account</h2>
            
            <input type="text" placeholder="Enter email address" name="Email" class="box" required>
            <input type="password" placeholder="Enter password" name="Password" class="box" required>
            <input type="password" placeholder="Confirm password" name="confirm_password" class="box" required>
            
            <input type="submit" value="Sign Up" id="submit"><br>
            <div class="sign"> 
                Already have an account?&nbsp;<a href="login.php">Login</a>
            </div>
        </form>
        
        <div class="side">
            <img src="../images/login.jpg" alt="">
        </div>
    </div>

    <!-- Show server-side validation errors -->
    <?php if (!empty($errors)) : ?>
        <div>
            <?php foreach ($errors as $error) : ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>

<?php include 'footer.php'; ?>
