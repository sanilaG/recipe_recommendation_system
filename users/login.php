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
                header("Location:index.php");
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

        // Function to show modal
        function showModal(message) {
            var modal = document.getElementById("errorModal");
            var modalMessage = document.getElementById("modalMessage");
            modalMessage.textContent = message;
            modal.style.display = "block";
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            var modal = document.getElementById("errorModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
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

        <!-- Show server-side validation errors in modal -->
        <?php if (!empty($errors)) : ?>
            <script>
                window.onload = function() {
                    <?php foreach ($errors as $error) : ?>
                        showModal("<?php echo addslashes($error); ?>");
                    <?php endforeach; ?>
                };
            </script>
        <?php endif; ?>
    </div>

    <!-- Modal for displaying errors -->
    <div id="errorModal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.4); padding-top: 60px;">
        <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 400px; text-align: center;">
            <span style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;" onclick="document.getElementById('errorModal').style.display='none'">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
