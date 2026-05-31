<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

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

// Retrieve user ID from session or request
if(isset($_GET['id'])){
    $user_id = urldecode($_GET['id']);
}

if ($user_id === null) {
    die(json_encode(["error" => "User ID not provided"]));
}

// Initialize an array to hold the fields to update
$updateFields = [];
$params = [];

// Check for incoming data and prepare update statements
if (!empty($_POST['username'])) {
    $updateFields[] = "username = ?";
    $params[] = urldecode($_POST['username']);
}

if (!empty($_POST['email'])) {
    $updateFields[] = "email = ?";
    $params[] = urldecode($_POST['email']);
}

if (!empty($_POST['password'])) {
    // Hash the password before storing it
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $updateFields[] = "password_hash = ?";
    $params[] = $hashedPassword;
}

if (!empty($_POST['type'])) {
    $updateFields[] = "account_type = ?";
    $params[] = urldecode($_POST['type']);
}

if (!empty($_POST['img'])) {
    $updateFields[] = "img = ?";
    $params[] = urldecode($_POST['img']);
    $new_img = $_POST['img'];
}

if (!empty($_POST['description'])) {
    $updateFields[] = "description = ?";
    $params[] = urldecode($_POST['description']);
}

if (!empty($_POST['agency'])) {
    $updateFields[] = "agency = ?";
    $params[] = urldecode($_POST['agency']);
}

// Check if there are fields to update
if (empty($updateFields)) {
    die(json_encode(["error" => "No data to update"]));
}

// Construct the SQL statement
$sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE user_id = ?";
$params[] = $user_id; // Add user_id as the last parameter

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters dynamically
$stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

// Execute the statement
if (!$stmt->execute()) {
    die(json_encode(["error" => "Update failed: " . $stmt->error]));
}else{
    echo json_encode(["success" => "User data updated successfully"]);
    $_SESSION['img']= $new_img;
    header("Location: /index.php");
    exit(); // Make sure to exit after the redirect
    }

// Close the prepared statement and the connection
$stmt->close();
$conn->close();

?>
