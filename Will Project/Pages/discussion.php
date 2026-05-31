<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();
// Create connection
$conn = new mysqli("sql311.infinityfree.com", "if0_37309654", "Casperw777", "if0_37309654_RealHomeDataBase");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize response and email array
$response = [];
$emails = [];

// Fetch emails from the database
$sql = "SELECT * FROM realmail";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $emails[] = [
                'title' => $row['title'],
                'sender' => $row['sender'],
                'receiver' => $row['receiver'], // Fixed typo: 'reciever' -> 'receiver'
                'subject' => $row['subject'],
                'message' => $row['message'],
                'name' => $row['name'],
                'id'=>$row['id'],
                'created_at' => $row['created_at'],
                'img' => $row['img'],
                'content_img' => $row['img_content'],
                'direct' =>$row['direct'],
                'likes' =>$row['likes'],
                'dislikes' =>$row['dislikes'],
                'comments' =>$row['comments']
            ]; // Store each email
        }
        // Move json_encode here to encode after building the emails array
        $retrieved = $emails;
    } else {
        $retrieved = json_encode([]); // No emails found
    }
} else {
    $retrieved = json_encode([]); // Query failed, return empty array
}

$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Board</title>
    <link rel="stylesheet" href="/css/main_styles.css">
<style>
    html, body {
        scroll-snap-type: none;
    }

        .main-container {
            display: flex;
            width: 100%;
            height: 100vh;  /* Full height of the viewport */
            position: relative;
        }

        .left-div {
            width: 30%;         /* Each div takes 50% width */
            height: 90vh;       /* Each div takes 90% height */
            position: absolute;
            bottom: 0;              
        }

        .right-div {
            width: 70%;         /* Each div takes 50% width */
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
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Center the circle */
            width: 30%;              /* Initial width (scales with parent size) */
            aspect-ratio: 1 / 1;      /* Maintain a square aspect ratio for scaling */
            border-radius: 50%;       /* Circular shape */
            transition: width 0.2s ease;  /* Smooth scaling */
        }

        .left-bottom {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center;     /* Center vertically */
        }

        .description {
            font-size: 18px;         /* Example font size */
            color: #333;             /* Optional text color */
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
            height: 90%;             /* Takes 90% height */
            position: relative;      /* Parent container for absolute positioning */
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
        /* Basic CSS Reset */

        .button {
            background: linear-gradient(to right, #7500c3, #8e30c4, #b72ed2);
            color: #fff;
            padding: 10px;
            border: 0px;
            font-size: 18px;
        }

        .button:hover{
            background: linear-gradient(to left, #7500c3,#8e30c4, #b72ed2);
            cursor: pointer;
        }

        .post_container{
            position: relative;
            margin: auto;
            justify-content: center;
            margin-top: 50px;
            width: 600px;
            height:fit-container;
            border-radius: 0.75rem;
            background: rgba(39, 7, 70, 0.695);
            padding: 2rem;
            color: #fff;
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
            width:100%;
            height:50px;
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
        #signup-description{    
            width:100%;
            border-radius:10px;
            opacity:0.5;
        }
        #post_image{
            width:100%; height:400px; border-radius:10%;margin-top:20px;margin-bottom:20px;border-width:5px;border-color:#461964;border-style:solid;
        }
        .discussion_text{
            width:100%;
            border-radius:20px;
            background-color:#461964;;
            padding-top:20px;
            padding-bottom:20px;
        }
        .discussion_header{
            width:100%;
            border-radius:20px;
            background-color:#461964;;
            padding-top:20px;
            padding-bottom:20px;
        }
        .post-container {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #460080, #2d003f);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
            font-family: 'Arial', sans-serif;
        }
        
        /* Hover effect for entire container */
        .post-container:hover {
            transform: translateY(-5px);
            box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.2);
        }
        
        /* Image styling */
        .post-image img {
            width: 100%;
            height: auto;
            display: block;
            filter: brightness(90%);
            transition: filter 0.3s ease;
        }
        
        /* Image hover effect */
        .post-container:hover .post-image img {
            filter: brightness(100%);
        }
        
        /* Content section styling */
        .post-content {
            padding: 20px;
            background-color:#27003d;
            border-radius: 0 0 12px 12px;
        }
        
        /* Title styling */
        .post-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: azure;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }
        
        /* Subject styling */
        .post-subject {
            font-size: 18px;
            font-weight: 600;
            color: azure;
            margin: 10px 0;
        }
        
        /* Description styling */
        .post-description {
            font-size: 15px;
            color: azure;
            line-height: 1.6;
        }
        
    </style>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="">
    <section>
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
        <div style="display:flex; flex-direction:column; position:relative; top:15%;">
            <div class="post_container">
            <h3>What's on your mind...</h3>
                <form action="add_post.php" method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column; align-items:center; width:100%; height:70%; position:relative; top:0; left:0;">
                    <input type="hidden" value="realhome@realestate.co.za" id="form_agent" name="reciever" required style="height:20px; width:100%;">

                    <label for="form_title">Title:</label>
                    <input type="text" id="form_title" name="title" required style="height:20px; width:100%;">

                    <label for="form_subject">Subject:</label>
                    <input type="text" id="form_subject" name="subject" required style="height:20px; width:100%;">
                    <div style="display:flex; flex-direction:column;width:100%;">
                        <img id="post_image" src="/images/backgrounds/placeholder.jpeg">
                        <div style="width:43%; height-fit-content">
                            <div style="display:flex; flex-direction:row;">
                                <div style="width:400px;">
                                    <label for="content-img">Upload an image:</label>
                                    <input type="file" id="content-img" name="content-img">
                                </div>
                            </div>    
                        </div>
                        <div style="width:43%; height-fit-content position:relative; right:0;">
                            <input type="hidden" value="public" id="form_message_type" name="direct" required style="height:20px; width:100%;">
                        </div>
                    </div>    
                    <input type="hidden" id="form_sender_email" name="sender" value="<?php echo htmlspecialchars($senderEmail); ?>" required>
                    <input type="hidden" id="form_sender_name" name="name" value="<?php echo htmlspecialchars($SenderName); ?>" required>
                    <input type="hidden" id="form_sender_img" name="img" value="<?php echo htmlspecialchars($Senderimg); ?>" required>

                    <div style="display:flex; align-content:center; justify-content:center; width:100%;">
                        <img style="width:60px; height:60px; border-radius:50%; margin-right:10px;" id="user-icon-description" src="/images/users/<?php echo $_SESSION['img']; ?>" alt="User Icon">
                        <input class="post_input" name="description" type="text" placeholder="Write your post here..." id="post-input">
                    </div>    
                    <div class="post_buttons">
                        <input type="submit" style="width:fit-content; margin:20px;" id="btnSubmit" class="filter-link" value="Post" />
                    </div>
                </form>
            </div>

            <div id="email-container"></div>
            
        </div>
        <div class="footer" style="position:relative; bottom:0; left:0;">
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
    document.getElementById('content-img').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = document.getElementById('post_image');
                    image.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
</script>
<script>
    function fetchemails() {
        const emailsDiv = document.getElementById("email-container");
        const emails = <?php echo json_encode($retrieved); ?>; // Correctly encode PHP array to JavaScript
        const current_user = "<?php echo $_SESSION['user'] ?>";
        
        console.log(emails); // Log the emails to verify the structure
        emailsDiv.innerHTML = ''; // Clear previous results

        emails.forEach(email => {
            const postDiv = document.createElement("div"); // Create a new div for each email
            let href = '';
            let deletebtn =''

            postDiv.className = "post_container"; // Set the class name for styling
            if (email.direct === "public") {

                if(email.content_img){
                    image_content =`<img src="/images/uploads/${email.content_img}">`
                }else{image_content=`<p></p>`}

                if(email.name === current_user){
                    deletebtn=`<a href="/Pages/delete_post.php?post_id=${email.id}" class="btnMain" style="margin-top:5px; margin-left:15px; font-size:20px;">Delete</a>`;
                }else{deletebtn=`<p></p>`}

                if(email.name === 'Guest'){
                deletebtn=``;
                href = ``; 
                } else{href = `href="/Pages/Agent_profile.php?visiting=${email.name}"`; }

                postDiv.innerHTML = `
                    <div class="user_container">
                        <img style="width:30px; height:30px; border-radius:50%;" src="/images/users/${email.img}">
                        <a ${href} style="margin-top:5px; margin-left:15px; font-size:20px;" class="btnMain">${email.name}</a>
                        ${deletebtn}
                    </div>
                    <div class="post-container">
                        <div class="post-image">
                        ${image_content}
                        </div>
                        <div class="post-content">
                            <h2 class="post-title">${email.title}</h2>
                            <h4 class="post-subject">${email.subject}</h4>
                            <p class="post-description">${email.message}</p>
                        </div>
                    </div>
                `;
                emailsDiv.appendChild(postDiv); // Append the postDiv to the emailsDiv
                
            }
        });
    }

    fetchemails();
</script>

</body>
</html>
