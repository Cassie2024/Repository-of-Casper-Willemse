<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "sql311.infinityfree.com";
$username = "if0_37309654";
$password = "Casperw777";
$dbname = "if0_37309654_RealHomeDataBase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['user'] ?>'s Profile's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/main_styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap');

        body {
            margin: 0;
            height: 100vh; /* 100% of the viewport height */
        }

        .main-container {
            display: flex;
            width: 100%;
            height: 100vh;  /* Full height of the viewport */
            position: relative;
        }

        .left-div, .right-div {
            width: 50%;         /* Each div takes 50% width */
            height: 90vh;       /* Each div takes 90% height */
            position: absolute;
            bottom: 0;          /* Start from the bottom of the container */
        }

        .left-div {
            left: 0;
            display: flex;
            flex-direction: column;  /* Arrange children vertically */
        }

        .left-top, .left-bottom {
            flex: 1;              /* Each will take 50% height */
        }

        .left-top {
            position: relative;   /* For centering the circle */
           
        }

        .center-circle {
            position: absolute;
            top: 5%;
            left: 5%;
            width: 10%;              /* Initial width (scales with parent size) */
            aspect-ratio: 1 / 1;      /* Maintain a square aspect ratio for scaling */
           
            border-radius: 50%;       /* Circular shape */
            transition: width 0.2s ease;  /* Smooth scaling */
        }

        .left-bottom {
            display: flex;
            align-items: center;     /* Center vertically */
            display:flex;
            flex-direction:column;
            border-radius:95px 95px 0 0;
            padding:30px;
            width:85%;
            display:flex;
            justify-items:center; 
            background: linear-gradient(180deg, rgba(217, 217, 217, 0.24) 0%, rgba(115, 115, 115, 0.00) 100%);
            position:relative;
            left:5%;
        }

        .description {
            font-size: 18px;         /* Example font size */
            color: azure;             /* Optional text color */
            text-align: center;      /* Ensure text is centered */
        }

        .right-div {
            right: 0;
            display: flex;
            flex-direction: column;  /* Arrange children vertically */
        }

        .right-top {
            height: 10%;             /* Takes 10% height */
            display: flex;
            justify-content: space-between; /* Space buttons evenly */
            align-items: center;     /* Center content */
            padding: 0 5px;         /* Optional padding */
        }

        .flex-button {
            flex: 1;                 /* Each button will grow equally */
            margin: 0 2px;          /* Add some margin between buttons */
            height: 100%;           /* Full height of the right-top */
        }

        .right-bottom {
            height: 90%;            /* Height of the container */
            width:100%;
            display: flex;            /* Enable flexbox */
            flex-direction: column;   /* Stack items vertically */
            position: relative;       /* Position relative for potential absolute children */
            overflow-y: auto;         /* Enable vertical scrolling */
            overflow-x: hidden;       /* Parent container for absolute positioning */
        }
        .right-bottom::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }

        .overlapping-divs {
            position: absolute;
            width: 100%;            /* Full width of the parent */
            height: 100%;           /* Full height of the parent */
        }

        .overlay-div {
            position: absolute;
            width: 100%;            /* Each overlay div takes full width */
            height: 100%;           /* Each overlay div takes full height */
            opacity: 0.8;           /* Optional for slight transparency */
            border: 1px solid #000; /* Optional border for visual clarity */
            display: none;          /* Initially hide all overlay divs */
        }

        /* Show the overlay div when active */
        .overlay-div.active {
            display: block;         /* Show the active overlay div */
        }

        .prof_heading{
            width:100%;
            height:20%;
        }
        .property-card {
            width: 100%;
            max-width: 800px;
            margin: 10px auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
        }

        .property-card:hover {
            transform: translateY(-5px);
        }

        .property-img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            margin-right: 15px;
        }

        .property-card h2 {
            font-size: 16px;
            margin: 0;
        }

        .property-card p {
            margin: 5px 0;
            font-size: 14px;
        }
        #pty-container {
            height: 500px;            /* Height of the container */
            width:100%;
            display: flex;            /* Enable flexbox */
            flex-direction: column;   /* Stack items vertically */
            position: relative;       /* Position relative for potential absolute children */
            overflow-y: auto;         /* Enable vertical scrolling */
            overflow-x: hidden;       /* Hide horizontal overflow */
        }
        .post_container{
            position: relative;
            margin: auto;
            justify-content: center;
            margin-top: 50px;
            width: 600px;
            height:fit-content;
            border-radius: 0.75rem;
            background: rgba(39, 7, 70, 0.695);
            padding: 2rem;
            color: #fff;
            overflow-wrap: break-word; /* Break long words if necessary */
            white-space: normal;  
            word-break: break-all;
        }
        .icon_button{
            background: linear-gradient(to right, #7500c3, #8e30c4, #b72ed2);
            color: #fff;
            width: 30px;
            height: 30px;
            border: 0px;
            font-size: 18px;  
        }
        .icon_button:hover{
            background: linear-gradient(to left, #7500c3,#8e30c4, #b72ed2);
            cursor: pointer;
        }

        .post_button{
            background: linear-gradient(to right, #7500c3, #8e30c4, #b72ed2);
            color: #fff;
            width: 60px;
            height: 30px;
            border: 0px;
            font-size: 18px;  
        }
        .post_button:hover{
            background: linear-gradient(to left, #7500c3,#8e30c4, #b72ed2);
            cursor: pointer;
        }
        .user_container{
            display: flex;
            flex-wrap: wrap;
            padding: 10px;
        }
        .post_container{
            margin-top: 10px;
        }
        .post{
            justify-content: center;
        }
        .post_input{
            height: 60px;
            width: 90%;
            margin-bottom: 20px;
        }

        .img_discription_button{
            background: linear-gradient(to right, #7500c3, #8e30c4, #b72ed2);
            color: #fff;
            width: 150px;
            height: 30px;
            border: 0px;
            font-size: 18px;  
        }
        .img_discription_button:hover{
            background: linear-gradient(to left, #7500c3,#8e30c4, #b72ed2);
            cursor: pointer;
        }
        input{
            width:80%;
            height:25px;
            border-radius:10px;
            opacity:0.5;
        }
        select{
            width:80%;
            height:25px;
            border-radius:10px;
            opacity:0.5;
        }
        #imageContainer{
            border-style:none;
        }
        #imageContainer::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }
        .form-container{
            border-radius:95px 0 0 0;
            padding:30px;
            width:100%;
            display:flex;
            flex-direction:column;
            align-items:center; 
            justify-items:center; 
            background: linear-gradient(180deg, rgba(217, 217, 217, 0.24) 0%, rgba(115, 115, 115, 0.00) 100%);
        }
    </style>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="Background Image">
    <div class="container">
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
                        <?php endif; ?>
                    </div>
                    <div class="logo_container">
                            <a href="/Pages/user_profile_management.php">
                                <img style="width:30px; border-radius:50%;" src="/images/users/<?php echo $_SESSION['img'] ?>" alt="User Icon">
                            </a>
                            <label id="profilename"><?php echo $_SESSION['user'] ?></label>
                    </div>
                </div>
            </div>
            
            <div class="main-container">
                <div class="left-div">
                    <div class="left-top">
                        <div class="prof_heading">
                            <div class="center-circle"><img style="width:100%; height:100%;" src="/images/users/<?php echo $response['img']; ?>" alt="Profile Image"></div>
                            <p style="position:relative; top:50px;" class="description"><?php echo $_SESSION['user']; ?> is working for <?php echo $_SESSION['agency']; ?></p>
                            <p style="position:relative; top:50px; width:80%; left:15%;" class="description"><?php echo $_SESSION['description']; ?></p>
                        </div>
                    </div>
                    <div class="left-bottom">
                        <table>
                            <tr>
                                <td><a class="btnMain" href="/Pages/wishlist.php">Wishlist/Favourites</a></td>
                            </tr>
                            <tr>
                                <td><label for="signup-username">Username:</label></td>
                                <td><p id="signup-username"><?php echo $_SESSION['user']; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="signup-email">Email:</label></td>
                                <td><p id="signup-email"><?php echo $_SESSION['email']; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="signup-accounttype">Account Type:</label></td>
                                <td><p id="signup-accounttype"><?php echo $_SESSION['account_type']; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="signup-agency">Agency:</label></td>
                                <td><p id="signup-agency"><?php echo $_SESSION['agency']; ?></p></td>
                            </tr>
                        </table> 
                    </div>    
                </div>
                <div class="right-div">
                    <div class="right-top">
                        Edit your profile as you please <?php echo $_SESSION['user']?>!!
                    </div>
                    <div class="right-bottom">
                        <form action="/Pages/edit_userdata.php?id=<?php echo $_SESSION['id']?>&user=<?php echo $_SESSION['user']?>" class="form-container" method="POST">
                            <label for="signup-username">Username:</label>
                            <input type="text" id="signup-username" name="username" >

                            <label for="signup-email">Email:</label>
                            <input type="email" id="signup-email" name="email">

                            <label for="signup-password">Password:</label>
                            <input type="password" id="signup-password" name="password">
                            
                            <label for="signup-accounttype">Account Type:</label>
                            <select id="signup-accounttype" name="type">
                                <option value="" disabled selected>Select an option</option>
                                <option value="User">User</option>
                                <option value="Agent">Agent</option>
                            </select>

                            <!-- Label for Icons -->
                            <label for="signup-icons">Select an Icon:</label>

                            <?php
                            // Directory containing the images
                            $dir = '../images/users';

                            // Check if directory exists
                            $images = [];
                            if (is_dir($dir)) {
                                if ($handle = opendir($dir)) {
                                    // Loop through directory files
                                    while (false !== ($file = readdir($handle))) {
                                        if ($file != '.' && $file != '..') {
                                            $images[] = htmlspecialchars($file); // Store valid image files
                                        }
                                    }
                                    closedir($handle);
                                }
                            }
                            ?>

                            <!-- Main container for icons -->
                            <div id="imageContainer" style="height: 100px; width: 80%; overflow-y: scroll; display: grid; grid-template-columns: repeat(auto-fill, 40px); grid-gap: 5px;">
                                <?php foreach ($images as $file): ?>
                                    <!-- Each image in the directory inside a 40px by 40px div -->
                                    <div class="image-div" style="width: 40px; height: 40px; cursor: pointer;">
                                        <img src="<?php echo $dir . '/' . $file; ?>" data-file="<?php echo $file; ?>"
                                            style="width: 100%; height: 100%;" class="thumbnail">
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Hidden input to store the selected image file value -->
                            <input type="hidden" id="selectedImage" name="img">

                            <!-- Optional Preview of the Selected Image -->
                            <div id="imagePreview" style="margin-top: 10px; height:80px;">
                                <label for="previewImage">selected Image:</label>
                                <img id="previewImage" src="" style="width: 40px;">
                            </div>

                            <label for="description">Description:</label>
                            <!-- Description Section -->
                            <div style="display:flex;flex-direction:column; width:80%;">
                                <textarea style="width:100%;height:40px;border-radius:10px; opacity:0.5;" id="signup-description" name="description"></textarea>
                            </div>

                            <label for="signup-agency">agency:</label>
                            <input type="text" id="signup-agency" name="agency" >
                            
                            <button class="btnMain" type="submit">Sign Up</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.querySelectorAll('.thumbnail').forEach(img => {
            img.addEventListener('click', function() {
                const selectedImageSrc = this.src;
                const selectedImageFile = this.getAttribute('data-file');
                const previewImage = document.getElementById('previewImage');
                const selectedImageInput = document.getElementById('selectedImage');

                // Update the hidden input value with the selected image file name
                selectedImageInput.value = selectedImageFile;

                // Show the selected image in the preview
                previewImage.src = selectedImageSrc;
                previewImage.style.display = 'block'; // Show the preview
            });
        });
    </script>
</body>
</html>
