<?php
session_start();
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = '../uploads/resources/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$uploadStatus = "";
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'audio/mp3', 'audio/m4a', 'audio/wav', 'audio/ogg', 'video/mp4', 'video/avi'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['files'])) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['files']['error'][$key] !== UPLOAD_ERR_OK) {
            $uploadStatus .= "Error with file upload: " . $_FILES['files']['error'][$key] . "<br>";
            continue;
        }

        $file_name = basename($_FILES['files']['name'][$key]);
        $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
        $target_file = $uploadDir . $file_name;

        if (in_array($_FILES['files']['type'][$key], $allowedTypes)) {
            if (move_uploaded_file($tmp_name, $target_file)) {
                $uploadStatus .= "File uploaded: " . htmlspecialchars($file_name) . "<br>";
            } else {
                $uploadStatus .= "Failed to upload: " . htmlspecialchars($file_name) . "<br>";
            }
        } else {
            $uploadStatus .= "File type not allowed: " . htmlspecialchars($file_name) . "<br>";
        }
    }
} else {
    $uploadStatus .= "No files uploaded or POST request not made.<br>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Resources</title>
    <link rel="stylesheet" href="..\css\styles.css">
    <link rel="stylesheet" href="..\css\header-footer.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Josefin+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="..\css\resources.css">
</head>
<body>
    <section class="hero">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="..\index.php"><img src="..\Assets\Images\Logo.png" alt="logo"></a>
                </div>
                <div class="navlinks">
                    <li><a href="..\index.php">Home</a></li>
                    <li><a href="timetable.php">Timetable</a></li>
                    <li><a href="discussion.php">Discussion</a></li>
                    <li><a href="share_resources.php">Share Resources</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
                    <button id="login-button" class="button">Log In</button>
                </div>
            </nav>
        </div>
    </section>
    <section class="main_resource_container">
        <img src="../Assets/Images/profile.png" class="resource_profileimg" alt="">
        <div class="resource_description">
            <h1>Programming Foundation</h1> 
            <p>
                Welcome the CTU-Buddy Resource page here all your assessments, textbooks, and tests will appear.
                the percentage you need to pass is 70% so study and work hard.
                Remember to upload assessments on time any late assesments will be subjected to 
                5% deduction.
            </p>
        </div>
    </section>    
    <section class="main_resource_container">
<div class="sub_resource_container" id="study_material">
    <h3>Study guides & Textbooks</h3>
    <?php
    $resourceDir = '../uploads/resources/';
    if (is_dir($resourceDir)) {
        if ($handle = opendir($resourceDir)) {
            echo '<div id="download_links">';
            while (false !== ($file = readdir($handle))) {
                // Check if the file is a PDF
                if ($file != '.' && $file != '..' && pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
                    echo '<div class="resource_links">';
                    echo '<p style="width: 200px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' . htmlspecialchars($file) . '</p>'; // Display the file name
                    echo ' <a href="' . $resourceDir . $file . '" download class="download-button">Download</a>';
                    echo '</div>';
                }
            }
            echo '</div>'; // Close the download links div
            closedir($handle);
        }
    } else {
        echo 'No resources available for download.';
    }
    ?>
</div>

<div class="sub_resource_container" id="download">
    <h3>Multi Media Resources</h3>
    <?php
    $resourceDir = '../uploads/resources/';
    if (is_dir($resourceDir)) {
        if ($handle = opendir($resourceDir)) {
            echo '<div id="download_links">';
            while (false !== ($file = readdir($handle))) {
                // Check if the file is an image, video, or audio
                if ($file != '.' && $file != '..') {
                    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $videoExtensions = ['mp4', 'avi', 'mov'];
                    $audioExtensions = ['mp3', 'wav', 'ogg'];

                    if (in_array($fileExtension, $imageExtensions) || 
                        in_array($fileExtension, $videoExtensions) || 
                        in_array($fileExtension, $audioExtensions)) {
                        echo '<div class="resource_links">';
                        echo '<p style="width: 200px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' . htmlspecialchars($file) . '</p>'; // Display the file name
                        echo ' <a href="' . $resourceDir . $file . '" download class="download-button">Download</a>';
                        echo '</div>';
                    }
                }
            }
            echo '</div>'; // Close the download links div
            closedir($handle);
        }
    } else {
        echo 'No resources available for download.';
    }
    ?>
</div>

        <div class="sub_resource_container" id="upload">
            <h3>Upload</h3>
            <form styles="display:flex; flex-wrap:wrap;" action="" method="POST" enctype="multipart/form-data">
        		<input class="download-button" type="file" name="files[]" multiple>
        		<button class="download-button" type="submit">Upload</button>
    		</form>
    	<div id="uploadStatus"><?php echo $uploadStatus; ?></div>
        </div>
    </section>   
    <section class="pending_resource_main_container">
        <div class="resource_description">
            <h1>Pending Assesments</h1>
         </div>
        <div class="pending_resource_container" id="pending">
            <h3>Pending</h3>

        </div>
    </section>  
<section>
    <footer>
        <div class="footer-container">
            <div class="footer-navlinks">
                <div class="left-links">
                    <li><a href="..\index.php">Home</a></li>
                    <li><a href="timetable.php">Timetable</a></li>
                    <li><a href="discussion.php">Discussion</a></li>
                </div>
                <div class="right-links">
                    <li><a href="share_resources.php">Share Resources</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
                </div>
            </div>
            <div class="footer-logo">
                <a href="..\index.php"><img src="..\Assets\Images\Logo.png" alt="logo"></a>
            </div>
            <div class="footer-right">
                <div class="socials">
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-youtube"></i></a>
                    <a href="#"><i class="fa fa-github"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                </div>
                <div class="copy">
                    <h2>© 2024 Code Innovators, Inc.</h2>
                </div>
            </div>
        </div>
    </footer>
</body>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const loginButton = document.getElementById("login-button");
    <?php if (isset($_SESSION['username'])): ?>
        loginButton.textContent = "Log Out";
        loginButton.onclick = function() {
            window.location.href = "../pages/logout.php";
        };
    <?php else: ?>
        loginButton.textContent = "Log In";
        loginButton.onclick = function() {
            window.location.href = "../pages/login.php";
        };
    <?php endif; ?>
});
</script>
</body>
</html>