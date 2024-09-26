<?php include '../pages/indexheader.php'; ?>

<?php
// Server-side validation starts here
$errors = [];
$success_message = "";  // Variable to store the success message

// Check if the current user is an admin (optional, if you want only admins to register other admins)
session_start();
// Assuming you store the user's role in the session when they log in
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    include '../includes/db.php';

    // Fetch form data
    $email = trim($_POST['Email']);
    $password = $_POST['Password'];
    $confirm_password = $_POST['confirm_password'];

    // By default, assign 'user' role
    $role = 'user';

    // If the logged-in user is an admin, allow them to create admin accounts
    if ($is_admin) {
        $role = 'admin'; // Assign 'admin' role if current user is admin
    }

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
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $hashed_password, $role);
        if ($stmt->execute()) {
            // Set success message to trigger popup
            $success_message = "User registered successfully!";
        } else {
            $errors[] = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function showPopup(message) {
            alert(message);  // Simple alert popup for success or error message
        }
    </script>
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
        <script>
            let errors = "<?php echo implode('\n', $errors); ?>";
            showPopup(errors);  // Display all errors in a popup
        </script>
    <?php endif; ?>

    <!-- Trigger success message -->
    <?php if (!empty($success_message)) : ?>
        <script>
            showPopup("<?php echo $success_message; ?>");  // Display success message in a popup
            window.location.href = 'notification.php';  // Redirect after displaying success message
        </script>
    <?php endif; ?>
</body>
</html>

<?php include '../pages/footer.php'; ?>
