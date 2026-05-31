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

// Initialize response array
$response = [];
$properties = [];

// Check if the visiting variable is set
if (isset($_GET['visiting'])) {
    // Decode the URL-encoded username
    $url_username = urldecode($_GET['visiting']);

    // Prepare the SQL statement to find the user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $url_username);
    
    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        // Bind the result
        $stmt->bind_result($id, $username, $email, $password_hash, $account_type, $creation_date, $img, $description, $agency);
        $stmt->fetch();

        // Store the user information in the response
        $response = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'url_username' => $url_username,
            'account_type' => $account_type,
            'creation_date' => $creation_date,
            'img' => $img,
            'description' => $description,
            'agency' => $agency
        ];
    }

    // Prepare and execute statement to fetch properties by agent username
    $stmt = $conn->prepare("SELECT * FROM properties WHERE agent = ?");
    $stmt->bind_param("s", $url_username);
    
    $stmt->execute();
    $stmt->store_result();

    // Check if properties exist for this agent
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $address, $city, $state, $country, $planet, $price, $type, $bath, $bed, $kit, $liv, $gar, $img, $desc, $customer, $agent, $date);
        
        // Fetch all properties
        while ($stmt->fetch()) {
            $properties[] = [
                'id' => $id,
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'planet' => $planet,
                'price' => $price,
                'type' => $type,
                'bath' => $bath,
                'bed' => $bed,
                'kit' => $kit,
                'liv' => $liv,
                'gar' => $gar,
                'img' => $img,
                'desc' => $desc,
                'customer' => $customer,
                'agent' => $agent,
                'date' => $date
            ];
        }
    }
    $stmt->close();
}

// Fetch emails from the database
$emails = [];
$sql = "SELECT * FROM realmail WHERE reciever = '$url_username'";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $emails[] = [
                'title' => $row['title'],
                'reciever' => $row['reciever'],
                'subject' => $row['subject'],
                'message' => $row['message'],
                'name' => $row['name'],
                'created_at' => $row['created_at'],
                'img' => $row['img'],
                'content_img' => $row['img_content'],
                'direct' => $row['direct'],
                'likes' => $row['likes'],
                'dislikes' => $row['dislikes'],
                'comments' => $row['comments']
            ];
        }
        $retrieved = $emails;
    } else {
        $retrieved = json_encode([]); // No emails found
    }
} else {
    $retrieved = json_encode([]); // Query failed, return empty array
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $url_username; ?>'s Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/main_styles.css">
    <style>

        body {
            margin: 0;
            height: 100vh; /* 100% of the viewport height */
            overflow: hidden;
        }
        body::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }   

        .main-container {
            display: flex;
            width: 100%;
            height: 100vh;  /* Full height of the viewport */
            position: relative;
        }

        .left-div, .right-div {
            width: 50%;         /* Each div takes 50% width */
            height: 78%;       /* Each div takes 90% height */
            position: absolute;
            bottom: 12%;          /* Start from the bottom of the container */
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
            justify-content: center; /* Center horizontally */
            align-items: center;     /* Center vertically */
            display:flex;
            flex-direction:column;
            height: 63%;
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
                     /* Height of the container */
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
        #pty-container::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
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
        
        .post-container {
            display: flex;
            flex-direction: column;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(135deg, #460080, #2d003f);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
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
            background-color: #27003d;
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
        
        .user_container{
            width:100%;
            height:50px;
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
            
            <div class="main-container">
                <div class="left-div">
                    <div class="left-top">
                        <div class="prof_heading">
                            <div class="center-circle"><img style="width:100%; height:100%;" src="/images/users/<?php echo $response['img']; ?>" alt="Profile Image"></div>
                            <p style="position:relative; top:50px;" class="description"><?php echo $response['username']; ?> is working for <?php echo $response['agency']; ?></p>
                            <p style="position:relative; top:50px;" class="description"><?php echo $response['description']; ?></p>
                        </div>
                    </div>
                    <div class="left-bottom">
                        <h2>Properties Listed by <?php echo $username?></h2>
                        <div id="pty-container"></div>
                    </div>    
                </div>
                <div class="right-div">
                    <div class="right-top">
                        Discussions linked to <?php echo $username?>
                    </div>
                    <div class="right-bottom">
                        <div id="email-container"></div>
                    </div>
                </div>
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
        </div>  
        </section>
    </div>
    <script>
        // Generate property cards
        document.addEventListener("DOMContentLoaded", function() {
            const propertyData = <?php echo json_encode($properties); ?>;
            const ptyContainer = document.getElementById('pty-container');

            if (propertyData.length > 0) {
                propertyData.forEach(property => {
                    const propertyCard = document.createElement('div');
                    propertyCard.className = 'property-card';
                    propertyCard.innerHTML = `
                        <img class="property-img" src="../images/properties/${property.img}" alt="${property.name}">
                        <div>
                            <h2>${property.name}</h2>
                            <p>${property.city}, ${property.state}, ${property.country}, ${property.planet}</p>
                            <p>${property.price} Credits</p>
                        </div>
                    `;
                    ptyContainer.appendChild(propertyCard);
                });
            } else {
                ptyContainer.innerHTML = '<p>No properties Listed Yet.</p>';
            }
        });

        function fetchemails() {
            const emailsDiv = document.getElementById("email-container");
            const emails = <?php echo json_encode($retrieved); ?>; // Correctly encode PHP array to JavaScript
            
            console.log(emails); // Log the emails to verify the structure
            emailsDiv.innerHTML = ''; // Clear previous results

            emails.forEach(email => {
                const postDiv = document.createElement("div"); // Create a new div for each email
                const href = `href="/Pages/profile.php?visiting=${email.name}"`;

                postDiv.className = "post_container"; // Set the class name for styling
                if (email.direct === "direct") {
                        if(email.content_img){
                            image_content =`<img src="/images/uploads/${email.content_img}">`
                        }else{image_content=`<p></p>`}
                        postDiv.innerHTML = `
                            <div class="user_container">
                                <img style="width:30px; height:30px; border-radius:50%;" src="/images/users/${email.img}">
                                <a ${href} style="margin-top:5px; margin-left:15px; font-size:20px;" class="btnMain">${email.name}</a>
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
