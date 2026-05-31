<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli("sql311.infinityfree.com", "if0_37309654", "Casperw777", "if0_37309654_RealHomeDataBase");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Initialize response array
$response = [];

// Prepare and bind the SQL statement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; // Plain password input for verification

    // Prepare the SQL statement to find the user by username
    $stmt = $conn->prepare("SELECT user_id, email, password_hash, account_type, img, description, creation_date, agency FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    
    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        // Bind the result (assuming password and other fields are in the order provided)
        $stmt->bind_result($user_id, $email, $hashedPassword, $account_type, $img, $description, $creation_date, $agency);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['type'] = $account_type;
            $_SESSION['img'] = $img;
            $_SESSION['description'] = $description;
            $_SESSION['creation_date'] = $creation_date;
            $_SESSION['agency'] = $agency;
            $_SESSION['id'] = $user_id;

            // Redirect to the index page
            $url = "/index.php";
            header("Location: $url");
            exit(); // Make sure to exit after the redirect
        } else {
            // Password is incorrect
            header("Location: /Pages/login_signup.php?i=1#login");
            exit(); // Make sure to exit after the redirect
        }
    } else {
        // No user found with that username
        header("Location: /Pages/login_signup.php?i=1#login");
        exit(); // Make sure to exit after the redirect
    }

    // Close statement
    $stmt->close();
}

// Output response as JSON
echo json_encode($response);

// Close the connection
$conn->close();
?>
