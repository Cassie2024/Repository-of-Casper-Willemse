<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("sql311.infinityfree.com", "if0_37309654", "Casperw777", "if0_37309654_RealHomeDataBase");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if (isset($_GET['pty_name'])) {
    // Decode the URL-encoded property name
    $ptyname = urldecode($_GET['pty_name']);
}

if (isset($_GET['listed_in'])) {
    // Decode the URL-encoded listing type
    $type = urldecode($_GET['listed_in']);
}

if (isset($_GET['status'])) {
    // Decode the URL-encoded status
    $status = urldecode($_GET['status']);
}

// Check if all required variables are set
if ($ptyname && $type && $status) {
    // Prepare and bind the SQL statement
    $addedDate = date("Y-m-d H:i:s");
    $stmt = $conn->prepare("INSERT INTO favourites (favourites_id, username, listed_in, property_name, added_date, status) VALUES (null, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_SESSION['user'], $type, $ptyname, $addedDate, $status);

    // Execute the statement and handle success or error
    if ($stmt->execute()) {
        $url_name = urlencode($ptyname);
        echo "New property added to favourites successfully!";
        header("Location: /Pages/property_description.php?name=$url_name");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error: Missing required parameters.";
}

// Close the database connection
$conn->close();
?>
