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

$row = null; // Initialize row variable
$properties = array(); // Initialize an empty array for the map properties

if (isset($_GET['name'])) {
    // Decode the URL-encoded property name
    $property_name = urldecode($_GET['name']);
    error_log('Property Name: ' . $property_name); // Log the property name for debugging

    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM properties WHERE property_name = ?");
    $stmt->bind_param("s", $property_name); // "s" indicates the type (string)

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Fetch the result
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc(); // Fetch the first result row

            // Store the property in the array for the map
            $properties[] = array(
                'address' => $row['address'] . ', ' . $row['city'] . ', ' . $row['state'] . ', ' . $row['country'],
                'title' => $row['property_name'],
                'agent' => $row['agent'],
                'img' => $row['img'],
                'planet' => $row['planet'],
                'price' => $row['price'],
                'state' => $row['state'],
                'description' => $row['description'],
                'country' => $row['country']
            );
            $target_agent = $row['agent'];
            $pty_id = $row['property_id'];

            // Log the fetched property for debugging
            error_log(print_r($row, true));
        } else {
            error_log("No property found with the name: " . htmlspecialchars($property_name)); // Log if no property found
        }
    } else {
        error_log("SQL Error: " . $stmt->error); // Log SQL execution error
    }

    // Close the statement
    $stmt->close();
} else {
    error_log("No property name provided."); // Log if no property name is provided
}

// Convert PHP array to JavaScript array
$json_properties = json_encode($properties);
if (json_last_error() !== JSON_ERROR_NONE) {
    error_log('JSON encoding error: ' . json_last_error_msg()); // Log any JSON encoding errors
}

if ($target_agent) {

    // Prepare the SQL statement to find the user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $target_agent);
    
    // Execute the statement
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows > 0) {
        // Bind the result
        $stmt->bind_result($id,$username, $email, $password_hash, $account_type, $creation_date,$img,$description,$agency);
        $stmt->fetch();

        // Store the user information in the response
        $agentpty = [
            'id' =>$id,
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'url_username' => $url_username,
            'account_type' => $account_type,
            'creation_date' => $creation_date,
            'img' => $img,
            'description' => $description,
            'src_pty' => $property_name,
            'agency' => $agency 
        ];
    }
    $encodedUsername = urlencode($url_username);
    // Close statement
    $stmt->close();
    }    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Description</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/main_styles.css">
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="../css/pty_description.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDq-xFOUlXXsIvMgdwv410toP8dBVk2rk&libraries=earth"></script>
    <style>
        #map {
            height: 100%;
            width: 100%;
            position: absolute;
        }
        #controls {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 5;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        }
        button {
            margin: 5px;
            padding: 10px;
            cursor: pointer;
        }
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap');
        .container {
            display: flex;
            width: 100%;
            height: 70%; /* Full viewport height */
            position:absolute;
            bottom:0;
            left:0;
        }

        .left-div, .right-div {
            width: 50%;
        }

        /* Left div divided into top 40% and bottom 60% */
        .left-div {
            display: flex;
            flex-direction: column;
        }

        .top-div {
            height: 40%;
            display: flex;
            justify-content: center; /* Center the circle horizontally */
            align-items: center; /* Center the circle vertically */
        }

        .bottom-div {
            height: 60%;
        }

        /* Right div divided into top 25% and bottom 75% */
        .right-div {
            display: flex;
            flex-direction: column;
        }

        .top-right-div {
            height: 25%;
        }

        .bottom-right-div {
            height: 75%;
        }

        /* Circle styles */
        .circle {
            width: 50%; /* Adjust as needed */
            height: 0;
            padding-bottom: 80%; /* Maintain aspect ratio for circle */
            border-radius: 50%; /* Make it circular */
            overflow: hidden; /* Hide overflow */
            position: relative; /* Position relative for img */
        }

        .circle-image {
            position: absolute; /* Position image absolutely */
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            width: 100%; /* Fill width of circle */
            height: auto; /* Maintain aspect ratio */
            transform: translate(-50%, -50%); /* Center the image */
            object-fit: cover; /* Scale the image to cover the circle */
        }


    </style>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="Background Image">
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
        <div class="pty_main_content">
            <div class="ptycontent_left">
                <div class="content_left">
                    <div class="pty_description">
                        <p>Name: <?php echo htmlspecialchars($row['property_name']); ?></p>
                        <p style="font-size: 16px;"><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                    <div class="pty_carousel">
                        <img class="pty_desc_img" src="/images/properties/<?php echo htmlspecialchars($row['img']); ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="ptycontent_right">
                <div class="card">
                    <div class="top_half">
                        <div class="region">
                            <p style="width: 300px; font-size: 15px;">Planet: <?php echo htmlspecialchars($row['planet']); ?></p>
                            <p style="width: 300px; font-size: 15px;margin:-10px;">Country: <?php echo htmlspecialchars($row['country']); ?></p>
                            <p style="width: 300px; font-size: 15px;">Property type: <?php echo htmlspecialchars($row['property_type']); ?></p>
                            <p style="width: 300px; font-size: 15px; margin:-10px;">City: <?php echo htmlspecialchars($row['city']); ?></p>
                        </div>
                        <div class="region_elipse"><img style="width:180px ;height: auto;" src="../images/planets/<?php echo htmlspecialchars($row['planet']); ?>.png" alt=""></div>
                    </div>
                    <div class="center">
                        <div class="left_half">
                            <div class="description_elipse_outer"><img class="bathrooms" src="../images/icons/bathroom.svg" alt=""></div>
                            <div class="description_elipse_inner"><img class="bedrooms" src="../images/icons/bedroom.svg" alt=""></div>
                            <div class="description_elipse_center"><img class="kitchens" src="../images/icons/kitchen.svg" alt=""></div>
                            <div class="description_elipse_inner"><img class="livingrooms" src="../images/icons/livingroom.svg" alt=""></div>
                            <div class="description_elipse_outer"><img class="garages" src="../images/icons/garage.svg" alt=""></div>
                        </div>
                        <div class="elipsebacking">
                            <img class="main_img" src="/images/properties/<?php echo htmlspecialchars($row['img']); ?>" alt="">
                        </div>
                    </div>
                    <div class="bottom_half">
                        <div class="region">
                            <p style="width: 420px; font-size: 15px; margin-top: 30px;">Agent name: <?php echo $agentpty['username']; ?></p>
                            <p style="width: 420px; font-size: 15px;margin-top:-10px;">Agency: <?php echo $agentpty['agency'];?></p>
                            <a class="filter-link" href="/Pages/Agent_profile.php?visiting=<?php echo $agentpty['username']?>"> Contact Agent</a>
                            <div style=" display:flex; flex-direction:row;">
                                <form action="/Pages/add_to_wishlist.php">
                                    <input type="hidden" name="user" value="<?php echo $url_username?>" required>
                                    <input type="hidden" name="pty_name" Value="<?php echo $property_name?>" required>
                                    <input type="hidden" name="listed_in" Value="favourites" required>
                                    <input type="hidden" name="status" Value="availiable" required>

                                    <button type="submit" class="filter-link" href="/Pages/Agent_profile.php?visiting=<?php echo $agentpty['username']?>">
                                        <img style="width:15px; margin-top:-5px; margin-bottom:5px;" src="/images/icons/favorite.png">
                                    </button>
                                </form>
                                <form action="/Pages/add_to_wishlist.php">
                                    <input type="hidden" name="user" value="<?php echo $url_username?>" required>
                                    <input type="hidden" name="pty_name" Value="<?php echo $property_name?>" required>
                                    <input type="hidden" name="listed_in" Value="wishlist" required>
                                    <input type="hidden" name="status" Value="availiable" required>

                                    <button type="submit" class="filter-link" href="/Pages/Agent_profile.php?visiting=<?php echo $agentpty['username']?>">
                                    <img style="width:15px; margin-top:-5px; margin-bottom:5px;" src="/images/icons/wishlist.png">
                                    </button>
                                </form>

                                <a class="filter-link" href="/Pages/purchase_property.php?name=<?php echo $property_name?>&pty_id=<?php echo $pty_id?>">Purchase</a>
                            </div>
                        </div>
                        
                        <div class="region_elipse"><img style="width:130px ;height: auto;" src="../images/users/<?php echo htmlspecialchars($agentpty['img']); ?>" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div id="map" style="width:100%; height:100%;"></div>
        <div style="position:absolute; display: flex; justify-content: space-around; align-items:center; width:100%; height:100px; bottom: 0;left: 0;background: linear-gradient(to right, #280045, #8300ba);">
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
            <div class="navbar">
                <div class="link_container">
                    <a class="nav_link" href="/">RealHome</a>
                    <a class="nav_link" href="/#about_us">About Us</a>
                    <a class="nav_link" href="/#contact_us">Contact Us</a>
                    <a class="nav_link" href="/Pages/discussion.php">Discussions</a>
                    <a class="nav_link" href="/Pages/properties.php">Properties</a>
                    <a class="nav_link" href="/Pages/solar_map.php">Map</a>
                    <a class="nav_link" href="/Pages/create_property_listing.html">list a property</a>
                    <a><img style="height: 100px;" src="/images/backgrounds/pixel_landscape.png" alt=""></a>
                </div>
            </div>
        </div> 
    </section>
<script>
    var map;
    var geocoder;
    var markers = [];
    var locations = <?php echo $json_properties ?>;

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 37.7749, lng: -122.4194 },
            zoom: 5
        });

        geocoder = new google.maps.Geocoder();

        // Generate markers for Earth locations using addresses
        locations.forEach(function(location) {
            addMarkerByAddress(location);
        });
    }

    // Function to add a marker to the map using address
    function addMarkerByAddress(location) {
    geocoder.geocode({ address: location.address }, function(results, status) {
        if (status === "OK") {
            var iconOptions = {
                url: '/images/icons/marker.png', // Path to your marker image
                anchor: new google.maps.Point(10, 20), // Default anchor
                scaledSize: new google.maps.Size(30, 40) // Adjust size as needed
            };

            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                icon: iconOptions // Using icon options for marker
            });

            // Info window with property details
            var infowindow = new google.maps.InfoWindow({
                content: `
                    <div style="width: 400px; height:650px;background: linear-gradient(to top, #280045, #8300ba); text-align:center; position:relative; overflow:hidden; color:white;">
                        <div style="width: 100%; height:fit-content;">
                            <div class="property_title">
                                <div style="width:100%; height:100px;">
                                    <p style="position:fixed; left:20px; top:50px;">${location.title}</p>
                                    <p style="position:fixed; right:50px; top:50px;">$${location.price}</p>
                                </div>
                            </div>
                            <div style="width: 100%;height: 10px;">
                                <p style="width: 80px; font-size: 18px;position:fixed;top:100px; left:20px;">Country:</p>
                                <p style="width: 70px; font-size: 15px;position:fixed;top:100px;left:100px;">${location.country}</p>
                                <div style="background: linear-gradient(to top, #280045, #8300ba);height: 80px;width: 80px;border-radius: 50%;position:fixed;top:60px; left:41%;">
                                    <img style="width: 100px;height:auto;border-radius: 50%;position:relative;left:-10px;top:2px;" src="/images/planets/${location.planet}.png">
                                </div>
                                <p style="width: 70px; font-size: 18px;position:fixed;top:100px;right:110px;">State:</p>
                                <p style="width: 100px; font-size: 15px;position:fixed;top:100px;right:30px;">${location.state}</p>
                            </div>
                            <p>${location.address}</p>
                            <div style="width:100%;">
                                <div style="background: radial-gradient(circle, #9501ff 0%, #59007f 42%,  #000000 100%);border-radius: 50%;width: 220px;height: 220px;position:relative;left:22%;align-content:center;justify-content:center;">
                                    <img style="width: 190px;height:190px;border-radius: 50%;border-color: #59007f;border-width: 3px;border-style: solid;position:relative;" src="/images/properties/${location.img}" alt="">
                                </div>
                                <div style="background: linear-gradient(to top, #280045, #8300ba);height: 70px;width: 70px;border-radius: 50%;margin: 10px; position:fixed; top:200px; left :30px;">
                                    <img class="bathrooms" src="../images/icons/bathroom.svg" alt="">
                                </div>
                                <div style="background: linear-gradient(to top, #280045, #8300ba);height: 70px;width: 70px;border-radius: 50%;margin: 10px; position:fixed; top:200px; right :30px;">
                                    <img class="bedrooms" src="../images/icons/bedroom.svg" alt="">
                                </div>
                                <div style="background: linear-gradient(to top, #280045, #8300ba);height: 70px;width: 70px;border-radius: 50%;margin: 10px; position:fixed; top:420px; right :41%;">
                                    <img class="kitchens" src="../images/icons/kitchen.svg" alt="">
                                </div>
                                <div style="background: linear-gradient(to top, #280045, #8300ba);height: 70px;width: 70px;border-radius: 50%;margin: 10px; position:fixed; top:350px; left :35px;">
                                    <img class="livingrooms" src="../images/icons/livingroom.svg" alt="">
                                </div>
                                <div style="background: linear-gradient(to top, #280045, #8300ba);height: 70px;width: 70px;border-radius: 50%;margin: 10px; position:fixed; top:350px; right :35px;">
                                    <img class="garages" src="../images/icons/garage.svg" alt="">
                                </div>
                            </div>
                            <div style="position:relative; top:90px;">
                                <p>${location.description}</p>
                            </div>
                        </div>
                    </div>
                `
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            markers.push(marker);
        } else {
            console.error("Geocode was not successful for the following reason: " + status);
        }
    });
    }

    
    window.onload = initMap;
</script>


</body>
</html>
