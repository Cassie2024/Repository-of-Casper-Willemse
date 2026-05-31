<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Retrieve email and password from POST request
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;

    // Check if email or password is empty
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        // Database connection
        $servername = "fdb1029.awardspace.net";
        $db_username = "4530757_clientsdata";
        $db_password = "6g4D9LErufCJVgc";
        $dbname = "4530757_clientsdata";

        // Create connection
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare SQL statement to retrieve the hashed password and username for the entered email
        $sql = "SELECT username, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user was found with the given email
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            $username = $row['username']; // Retrieve the username from the query result

            // Verify the password using password_verify
            if (password_verify($password, $hashed_password)) {
                // Set session variable for logged-in user
                $_SESSION['username'] = $username; // Store the username in session
                
                // Redirect to index.php on successful login
                header("Location: ../index.php");
                exit(); // Ensure no further code is executed after the redirect
            } else {
                echo "<script>alert('Incorrect password!');</script>";
            }
        } else {
            echo "<script>alert('No user found with that email.');</script>";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/register-login.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Josefin+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <a href="../index.php"><div class="backbutton"><i class="fa fa-arrow-left"></i></div></a>
    <div class="background" id="login-back"></div>
    <div class="form-container" id="login">
        <p class="title">Login</p>
        <form method="POST" class="form">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="" required>
                <div class="forgot">
                    <a href="reset_password.html">Forgot Password?</a>
                </div>
            </div>
            <button id="sign" class="button" type="submit">Sign In</button>
        </form>
        <div class="social-message">
            <div class="line"></div>
            <p class="message">Login with social accounts</p>
            <div class="line"></div>
        </div>
        <div class="social-icons">
            <a href="#" class="icon" onclick="showNotImplemented('Google'); return false;"><i class="fa fa-google"></i></a>
            <a href="#" class="icon" onclick="showNotImplemented('Twitter'); return false;"><i class="fa fa-twitter"></i></a>
            <a href="#" class="icon" onclick="showNotImplemented('GitHub'); return false;"><i class="fa fa-github"></i></a>
        </div>
        <p class="signup">Don't have an account?
            <a rel="noopener noreferrer" href="register.php" class="">Sign Up</a>
        </p>
    </div>
    
    <script>
        function showNotImplemented(platform) {
            alert(platform + " login is not implemented yet.");
        }
    </script>
</body>
</html>
