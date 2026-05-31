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

// Initialize response arrays
$properties = [];
$user = [];

// Check if 'user' parameter is set in the GET request
if (isset($_SESSION['user'])) {

    // Fetch properties by agent username
    $stmt = $conn->prepare("SELECT * FROM properties WHERE agent = ?");
    $stmt->bind_param("s", $url_username);
    
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $address, $city, $state, $country, $planet, $price, $type, $bath, $bed, $kit, $liv, $gar, $img, $desc, $customer, $agent, $date);
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
                $ptyimg = $img;
            }
        }
    } else {
        echo json_encode(["error" => "Failed to fetch properties: " . $stmt->error]);
    }
    $stmt->close();

    // Fetch user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $url_username);
    
    if ($stmt->execute()) {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $email, $password_hash, $account_type, $creation_date, $img, $description, $agency);
            $stmt->fetch();
            $user = [
                'id' => $id,
                'username' => $username,
                'email' => $email,
                'password_hash' => $password_hash,
                'url_username' => $url_username,
                'account_type' => $account_type,
                'creation_date' => $creation_date,
                'img' => $img,
                'description' => $description,
                'agency' => $agency
            ];
            $_SESSION['user'] = $username; // Store logged-in user in session
            $_SESSION['user_img'] = $img; // Store logged-in user image in session
        }
    } else {
        echo json_encode(["error" => "Failed to fetch user: " . $stmt->error]);
    }
    $stmt->close();
}

// Fetch favourites from the database
$userlist = [];
$sql = "SELECT * FROM favourites";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $userlist[] = [
            'username' => $row['username'],
            'name' => $row['property_name'],
            'status' => $row['status'],
            'listed_in' => $row['listed_in'],
            'added_date' => $row['added_date']
        ];
    }
} else {
    echo json_encode(["error" => "Failed to fetch favourites: " . $conn->error]);
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist/Favourites</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/main_styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap');

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
            height: 75vh;       /* Each div takes 90% height */
            position: relative;
            top: 13%;          /* Start from the bottom of the container */
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
            height: 100%;            /* Height of the container */
            width:100%;
            display: flex;            /* Enable flexbox */
            flex-direction: column;   /* Stack items vertically */
            position: relative;       /* Position relative for potential absolute children */
            overflow-y: auto;         /* Enable vertical scrolling */
            overflow-x: hidden;       /* Parent container for absolute positioning */
        }
        .left-bottom::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
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
            height: 100%;            /* Height of the container */
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
            text-decoration:none;
            color:Azure;
        }
        a { color: inherit; }

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
    </style>
</head>
<body>
    <div class="main-container">
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
        <div class="left-div">
            <div class="left-bottom">
              <?php if (!empty($userlist)): ?>
                    <h2 style="color:azure;">wishlist</h2>
                    <?php foreach ($userlist as $wishlist): ?>
                        <?php if ($wishlist['listed_in'] === 'wishlist' && $wishlist['username'] == $_SESSION['user']): ?>
                            <a href="/Pages/property_description.php?user=<?php echo urlencode($_SESSION['user']); ?>&name=<?php echo urlencode($wishlist['name']); ?>" class="property-card">
                                <?php foreach ($properties as $property): ?>
                                    <?php if ($property['name'] === $wishlist['name']): ?>
                                        <img class="property-img" src="/images/properties/<?php echo $property['img']; ?>" alt="Property Image">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div>
                                    <h3><?php echo $wishlist['name']; ?></h3>
                                    <p>Status: <?php echo $wishlist['status']; ?></p>
                                    <p>Listed in: <?php echo $wishlist['listed_in']; ?></p>
                                    <p>Added Date: <?php echo $wishlist['added_date']; ?></p>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No wishlists found.</p>
                <?php endif; ?>

            </div>
        </div>
        <div class="right-div">
            <div class="right-bottom">
                <?php if (!empty($userlist)): ?>
                    <h2 style="color:azure;">Favourites</h2>
                    <?php foreach ($userlist as $favorite): ?>
                        <?php if ($favorite['listed_in'] === 'favourites' && $favorite['username'] == $_SESSION['user']): ?>
                            <a href="/Pages/property_description.php?user=<?php echo urlencode($_SESSION['user']); ?>&name=<?php echo urlencode($favorite['name']); ?>" class="property-card">
                                <?php foreach ($properties as $property): ?>
                                    <?php if ($property['name'] === $favorite['name']): ?>
                                        <img class="property-img" src="/images/properties/<?php echo $property['img']; ?>" alt="Property Image">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                    <div>
                                        <h2><?php echo $favorite['name']; ?></h2>
                                        <p><?php echo $favorite['added_date']; ?></p>
                                        <p>Status: <?php echo $favorite['status']; ?></p>
                                    </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No favorites found.</p>
                <?php endif; ?>
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
</body>
</html>
