<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/register-login.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Josefin+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .message-box {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            text-align: center;
        }
        .success {
            background-color: #28a745; /* Green */
        }
        .error {
            background-color: #dc3545; /* Red */
        }
    </style>
</head>
<body>
    <a href="../index.php"><div class="backbutton"><i class="fa fa-arrow-left"></i></div></a>
    <div class="background" id="register-back"></div>
    <div class="form-container" id="register">
        <p class="title">Register</p>
        
        <form class="form" action="register.php" method="POST">
            <div class="input-group">
                <label for="full-name">Full Name</label>
                <input type="text" name="full-name" id="full-name" placeholder="" required>
            </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="" required>
            </div>
            <div class="input-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password" name="confirm-password" id="confirm-password" placeholder="" required>
            </div>
            <button id="sign" class="button" type="submit">Sign Up</button>
        </form>

        <div class="social-message">
            <div class="line"></div>
            <p class="message">Sign up with social accounts</p>
            <div class="line"></div>
        </div>
        <div class="social-icons">
            <a href="#" class="icon" onclick="showNotImplemented('Google'); return false;"><i class="fa fa-google"></i></a>
            <a href="#" class="icon" onclick="showNotImplemented('Twitter'); return false;"><i class="fa fa-twitter"></i></a>
            <a href="#" class="icon" onclick="showNotImplemented('GitHub'); return false;"><i class="fa fa-github"></i></a>
        </div>
        <p class="signup">Already have an account?
            <a rel="noopener noreferrer" href="login.php" class="">Login</a>
        </p>

        <!-- Add this script tag before the closing </body> tag -->
        <script>
            function showNotImplemented(platform) {
                alert(platform + " registration is not implemented yet.");
            }
        </script>

        <!-- Message Box for Registration Status -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fullname = $_POST['full-name'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm-password'];

            // Validate input fields
            if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
                echo '<div class="message-box error">Please fill in all fields.</div>';
            } elseif ($password !== $confirmPassword) {
                echo '<div class="message-box error">Passwords do not match.</div>';
            } else {
                // Include config file
                include('../config.php');

                // Check if the username or email already exists in the database
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
                $stmt->bind_param("ss", $email, $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<div class="message-box error">Email or username already exists.</div>';
                } else {
                    // Hash the password using password_hash()
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert new user into the database
                    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);
                    $stmt->execute();

                    // Show success message and clear form
                    echo '<div class="message-box success">Registration successful! You can now <a href="login.php">login</a>.</div>';
                }

                $stmt->close();
                $conn->close();
            }
        }
        ?>
    </div>
</body>
</html>
