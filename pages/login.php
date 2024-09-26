<?php include 'header.php'; ?>
<?php
session_start();
include '../includes/db.php';

$errors = []; // Initialize errors array

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['Email']); // Use 'Email' to match the form field name
    $password = $_POST['Password'];

    // Server-side validation
    if (empty($email) || empty($password)) {
        $errors[] = "Email and Password are required.";
    }

    if (empty($errors)) {
        // Query the database using the correct column
        $query = "SELECT * FROM users WHERE email = ?"; // Changed 'username' to 'email'
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Store user information in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email']; // Changed 'username' to 'email'
                header("Location:../users/index.php");
                exit;
            } else {
                $errors[] = "Incorrect password!";
            }
        } else {
            $errors[] = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function validateLoginForm() {
            var email = document.getElementsByName('Email')[0].value;
            var password = document.getElementsByName('Password')[0].value;

            if (email.trim() === "" || password.trim() === "") {
                alert("Email and Password are required.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="center">
        <form action="login.php" method="POST" class="form" onsubmit="return validateLoginForm()">
            <h2>Login</h2>
            <input type="text" placeholder="Enter email address" name="Email" class="box">
            <input type="password" placeholder="Enter password" name="Password" class="box">
            <input type="submit" value="Login" id="submit"><br>
            <a href="#">Forget password?</a>
            <div class="sign">
                Create new account&nbsp;&nbsp;<a href="Register.php">Sign Up</a>
            </div>
        </form>

        <div class="side">
            <img src="../images/login.jpg" alt="Login Image">
        </div>

        <!-- Show server-side validation errors -->
        <?php if (!empty($errors)) : ?>
            <div>
                <?php foreach ($errors as $error) : ?>
                    <p style="color: red;"><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
