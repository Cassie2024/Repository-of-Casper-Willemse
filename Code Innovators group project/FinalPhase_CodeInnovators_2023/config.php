<?php
// Database configuration
$servername = "fdb1029.awardspace.net"; // Your database server name
$db_username = "4530757_clientsdata"; // Your database username
$db_password = "6g4D9LErufCJVgc"; // Your database password
$dbname = "4530757_clientsdata"; // Your database name

// Create a connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
