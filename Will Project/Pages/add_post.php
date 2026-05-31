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

$uploadStatus = "";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = '../images/uploads/'; // Directory to save uploads
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

    // Check if the file input is set
    if (isset($_FILES['content-img'])) {
        // Check for upload errors
        if ($_FILES['content-img']['error'] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['content-img']['name']);
            $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
            $target_file = $uploadDir . $file_name;

            // Check if the uploaded file type is allowed
            if (in_array($_FILES['content-img']['type'], $allowedTypes)) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['content-img']['tmp_name'], $target_file)) {
                    $uploadStatus = "File uploaded successfully: " . htmlspecialchars($file_name);
                } else {
                    $uploadStatus = "Failed to upload file: " . htmlspecialchars($file_name);
                }
            } else {
                $uploadStatus = "File type not allowed: " . htmlspecialchars($file_name);
            }
        } else {
            $uploadStatus = "Error with file upload: " . $_FILES['content-img']['error'];
        }
    } else {
        $uploadStatus = "No file uploaded.";
    }

    $reciever = $conn->real_escape_string($_POST['reciever']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['description']);
    $title = $conn->real_escape_string($_POST['title']);
    $msgtype = $conn->real_escape_string($_POST['direct']);

    $SenderName = $_SESSION['user'];
    $Senderimg = $_SESSION['img'];

    $insertSql = "INSERT INTO realmail (reciever, subject, message, title, name, created_at, img, img_content, direct) 
                  VALUES ('$reciever', '$subject', '$message', '$title', '$SenderName', NOW(), '$Senderimg', '$file_name', '$msgtype')";
    
    if (!$conn->query($insertSql)) {
        die(json_encode(["error" => "Insert failed: " . $conn->error]));
    } else {
        if($msgtype === 'direct'){
            header("Location: /Pages/Agent_profile.php?visiting=$reciever");
            exit; // End script execution after the redirect
        } else{
            header("Location: /Pages/discussion.php");
            exit;
        }
    }
}

// dont remove this
$usernames = [];

// Prepare and execute the SQL statement to select all usernames
$sql = "SELECT username FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch each username and store it in the array
    while ($row = $result->fetch_assoc()) {
        $usernames[] = $row['username'];
    }
    // Output the usernames array as a JSON response
    echo json_encode(['usernames' => $usernames]);
} else {
    // If no usernames are found, return an empty array
    echo json_encode(['usernames' => []]);
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/landing_page.css">
    <link rel="stylesheet" href="/css/main_styles.css">
    <style>
        input{
            width:100%;
            height:25px;
            border-radius:10px;
            opacity:0.5;
        }
        select{
            width:100%;
            height:25px;
            border-radius:10px;
            opacity:0.5;
        }
        #form_description{
            height:200px;
            width:100%;
            border-radius:10px;
            opacity:0.5;
        }
        .post_form{
            position: relative;
            margin: auto;
            justify-content: center;
            width: 600px;
            height:fit-container;
            border-radius: 0.75rem;
            background: rgba(39, 7, 70, 0.695);
            padding: 2rem;
            color: #fff;
        }
    </style>
    <title>Contact form</title>
</head>
<body>
    <img class="background_image" src="../images/backgrounds/subtle_stars.png" alt="">
    <section class="section1">
        <div class="nav_container">
            <div class="logo">
                <img class="logo_img" src="/images/icons/Realhome.png" alt="Logo">
            </div>
            <div class="navbar">
                <div class="link_container">
                    <a class="nav_link" href="/">RealHome</a>
                    <a class="nav_link" href="/#about_us">About Us</a>
                    <a class="nav_link" href="/#contact_us">Contact Us</a>
                    <a class="nav_link" href="/Pages/discussion.php">Discussions</a>
                    <a class="nav_link" href="/Pages/solar_map.php">Map</a>
                    <a class="nav_link" href="/Pages/properties.php">Properties</a>
                    <a class="nav_link" href="/Pages/create_property_listing.php">List a Property</a>
                </div>
                <div class="button_container">
                    <?php if ($_SESSION['user'] !== 'guest'): ?>
                        <a class="btnMain" href="/Pages/logout.php">Log Out</a>
                    <?php else: ?>
                        <a class="btnMain" href="/Pages/login_signup.php?i=1#login">Log In</a>
                        <a class="btnMain" href="/Pages/login_signup.php?i=1#signup">Sign up</a>
                    <?php endif; ?>
                </div>
                <div class="logo_container">
                    <?php if ($_SESSION['user'] !== 'guest'): ?>
                        <a href="/Pages/user_profile_management.php">
                            <img style="width:30px; border-radius:50%;" src="/images/users/<?php echo $_SESSION['img'] ?>" alt="User Icon">
                        </a>
                        <label id="profilename"><?php echo $_SESSION['user'] ?></label>
                    <?php else: ?>
                        <a>
                            <img style="width:30px; border-radius:50%;" src="/images/users/<?php echo $_SESSION['img'] ?>" alt="User Icon">
                        </a>
                        <label id="profilename"><?php echo $_SESSION['user'] ?></label>
                    <?php endif; ?>    
                </div>
            </div>
        </div>    
        <div style="display:flex;flex-direction:column;">
            <h1>Create a Post</h1>
            <form action="add_post.php" method="POST" enctype="multipart/form-data" class="post_form">
                <label for="form_agent">To:</label>
                <select id="form_agent" name="reciever" required style="height:20px; width:100%;" name="usernames" id="usernames">
                    <option value="">Select a Username</option>
                    <?php foreach ($usernames as $username): ?>
                        <option value="<?php echo htmlspecialchars($username); ?>"><?php echo htmlspecialchars($username); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="form_title">Title:</label>
                <input type="text" id="form_title" name="title" required style="height:20px; width:100%;">

                <label for="form_subject">Subject:</label>
                <input type="text" id="form_subject" name="subject" required style="height:20px; width:100%;">
                <div style="display:flex; flex-direction:row;">
                    <div style="width:43%; height-fit-content">
                        <div style="display:flex; flex-direction:row;">
                            <div style="width:fit-content; position:relative; right:50%;">
                                <img id="image" />
                            </div>
                            <div style="width:400px;">
                                <label for="content-img">Upload an image:</label>
                                <input type="file" id="content-img" name="content-img" required />
                            </div>
                        </div>    
                    </div>
                    <div style="width:43%; height-fit-content position:relative; right:0;">
                        <label for="form_message_type">Is this a direct message?:</label>
                        <select id="form_message_type" name="direct" style="height:20px; width:100%;">
                            <option value="public">Public</option>
                            <option value="direct">Direct</option>
                        </select>
                    </div>
                </div>    
                <input type="hidden" id="form_sender_email" name="sender" value="<?php echo htmlspecialchars($senderEmail); ?>" required>
                <input type="hidden" id="form_sender_name" name="name" value="<?php echo htmlspecialchars($SenderName); ?>" required>
                <input type="hidden" id="form_sender_img" name="img" value="<?php echo htmlspecialchars($Senderimg); ?>" required>

                <label for="form_description">Description:</label>
                <textarea id="form_description" name="description" required></textarea>

               <input type="submit" style="width:fit-content; margin:20px;" id="btnSubmit" class="filter-link" value="Upload" />
            </form>
        </div>    

        <div class="footer">
            <div class="social_links">
                <a>Follow us on</a>
                <a class="nav_link" href="#section1">X</a>
                <a class="nav_link" href="#section2">Instagram</a>
                <a class="nav_link" href="#section3">LinkedIn</a>
                <a class="nav_link" href="#section1">Facebook</a>
            </div>
            <div class="footer_logo">
                <img class="footer_logo_img" src="/images/icons/Realhome.png" alt="">
            </div>
            <div>
                <div class="link_container">
                    <a class="nav_link" href="/">RealHome</a>
                    <a class="nav_link" href="/#about_us">About Us</a>
                    <a class="nav_link" href="/#contact_us">Contact Us</a>
                    <a class="nav_link" href="/Pages/discussion.php">Discussions</a>
                    <a class="nav_link" href="/Pages/properties.php">Properties</a>
                    <a class="nav_link" href="/Pages/solar_map.php">Map</a>
                    <a class="nav_link" href="/Pages/create_property_listing.php">list a property</a>
                    <a><img style="height: 100px;" src="/images/backgrounds/pixel_landscape.png" alt=""></a>
                </div>
            </div>
        </div> 
    </section>
    <script>

    </script>
</body>
</html>
