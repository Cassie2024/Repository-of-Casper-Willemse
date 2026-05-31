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

$uploadStatus = ""; // Initialize upload status message

// Prepare and bind the SQL statement
// Prepare and bind the SQL statement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $description = $conn->real_escape_string($_POST['description']);
    $type = $conn->real_escape_string($_POST['type']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $img = $conn->real_escape_string($_POST['img']);
    // Proceed with database insertion
    $stmt = $conn->prepare("INSERT INTO users (user_id, username, email, password_hash, account_type, img, description) VALUES (null, ?, ?, ?, ?, ?, ?)");

    // Bind the parameters
    $stmt->bind_param("ssssss", $username, $email, $hashedPassword, $type, $img, $description);


    if ($stmt->execute()) {
        $uploadStatus .= "User added successfully!<br>";
        header("Location: /Pages/login_signup.php?i=1#login");
        exit(); // Make sure to exit after the redirect
    } else {
        $uploadStatus .= "Error adding user: " . $stmt->error . "<br>";
    }

}

echo $uploadStatus; // Output upload status for debugging
$conn->close();
?>
