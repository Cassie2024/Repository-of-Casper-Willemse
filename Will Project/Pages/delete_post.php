<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
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

// Make sure 'post_id' is set
if (isset($_GET['post_id'])) {
    $post_id = urldecode($_GET['post_id']);
} else {
    die(json_encode(["error" => "'post_id' not provided"]));
}

if ($post_id) {
    // Delete the mail from the realmail table
    $deleteSql = "DELETE FROM realmail WHERE id = '$post_id'";

    if (!$conn->query($deleteSql)) {
        die(json_encode(["error" => "Delete failed: " . $conn->error]));
    } else {
        echo json_encode(["success" => "Mail deleted successfully. ID: ".$post_id]);
        header("Location: /Pages/discussion.php");
        exit; // End script execution after the redirect
    }
} else {
    die(json_encode(["error" => "Invalid 'post_id'"]));
}

$conn->close();
?>
