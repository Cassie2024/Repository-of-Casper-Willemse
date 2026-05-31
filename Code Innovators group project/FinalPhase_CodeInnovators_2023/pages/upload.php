<?php
// Database connection
$servername = "fdb1029.awardspace.net";
$username = "4530757_clientsdata";
$password = "6g4D9LErufCJVgc";
$dbname = "4530757_clientsdata";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the uploaded file details
    $fileName = $_FILES['image']['name'];
    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileSize = $_FILES['image']['size'];
    $fileType = $_FILES['image']['type'];

    // Specify the upload directory
    $uploadDir = 'uploads/';
    $targetFilePath = $uploadDir . basename($fileName);

    // Check if the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
        // Prepare SQL statement to insert into the database
        $stmt = $conn->prepare("INSERT INTO posts (username, post_text, image) VALUES (?, ?, ?)");
        $username = "YourUsername"; // Replace with the logic to get the logged-in username
        $postText = $_POST['post_text']; // Assuming you have a textarea with name 'post_text'
        $stmt->bind_param("sss", $username, $postText, $targetFilePath);

        if ($stmt->execute()) {
            echo "Post created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading the file.";
    }
}

$conn->close();
?>
