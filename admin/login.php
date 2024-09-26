<?php
session_start(); // Start the session

// Include database connection
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch form data
    $email = trim($_POST['Email']);
    $password = $_POST['Password'];

    // Prepare SQL query to check user credentials
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $role);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect to the appropriate dashboard based on the role
            if ($role === 'admin') {
                header("Location: dashboard.php"); // Redirect to admin dashboard
            } else {
                header("Location: ../user/index.php"); // Redirect to user dashboard
            }
            exit(); // Terminate the script after redirecting
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="center">
        <form action="login.php" method="POST" class="form">
            <h2>Login</h2>
            <input type="text" placeholder="Enter email address" name="Email" class="box" required>
            <input type="password" placeholder="Enter password" name="Password" class="box" required>
            <input type="submit" value="Login" id="submit"><br>
            <div class="sign">
                <a href="#">Forget password?</a>
            </div>
            <div class="sign">
                Create new account&nbsp;&nbsp;<a href="register.php">Sign Up</a>
            </div>
        </form>

        <div class="side">
            <img src="../images/login.jpg" alt="Login Image">
        </div>
    </div>

    <?php if (isset($error_message)) : ?>
        <script>
            alert("<?php echo $error_message; ?>");
        </script>
    <?php endif; ?>
</body>
</html>
