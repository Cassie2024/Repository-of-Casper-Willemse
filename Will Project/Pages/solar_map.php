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

// Initialize an empty array for the map properties
$properties = array(); 

// Prepare the SQL query
$stmt = $conn->prepare("SELECT * FROM properties");

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results and store each property in the array
while ($row = $result->fetch_assoc()) {
    // Store the property in the array for the map
    $properties[] = array(
        'address' => $row['address'] . ', ' . $row['city'] . ', ' . $row['state'] . ', ' . $row['country'],
        'title' => $row['property_name'],
        'agent' => $row['agent'], // Adjusted to use the correct column
        'img' => $row['img'],
        'planet' => $row['planet'],
        'price' => $row['price'],
        'state' => $row['state'],
        'description' => $row['description'],
        'country' => $row['country'],
        'city' => $row['city']
    );
}

// Convert PHP array to JavaScript array
$json_properties = json_encode($properties);

// Close the statement and connection
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solar System</title>
    <link rel="stylesheet" href="/css/main_styles.css">
    <link rel="stylesheet" href="/css/landing_page.css">
    <link rel="stylesheet" href="/css/property_page.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=******&libraries=earth"></script>
    <style>
        .solar-system {
            position: relative;
            width: 600px;
            height: 600px;
            transform-origin: center center;
            transition: transform 0.2s;
        }

        .zoom-1 { transform: scale(0.5); }
        .zoom-2 { transform: scale(1); }
        .zoom-3 { transform: scale(1.5); }
        .zoom-4 { transform: scale(2); }

        .sun {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100px;
            height: 100px;
            background-color: yellow;
            border-radius: 50%;
            z-index: 3; /* Sun on top */
        }

        .orbit {
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            border: 1px dashed rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
        }

        .orbit-1 { width: 420px; height: 420px;}
        .orbit-2 { width: 520px; height: 520px;}
        .orbit-3 { width: 620px; height: 620px;}
        .orbit-4 { width: 820px; height: 820px;}
        .orbit-5 { width: 235px; height: 235px;}
        .orbit-6 { width: 1360px; height: 1360px; }

        .asteroid-belt {
            width: 1260px;
            height: 1260px;
            border-radius: 50%;
            border-style: none;
            transform: translate(-50%, -50%);
            z-index: 0; /* Asteroid belt behind */
        }

        .planet {
            position: absolute;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
/* Different colors for each planet */
        .planet-1 {
            position: absolute;
            left:100%;
            top:52%;
        }

        .planet-2 {
            position: absolute;
            left:100%;
            top:52%;
        }

        .planet-3 {
            position: absolute;
            left:100%;
            top:51%;
        }
        .planet-4 {
            position: absolute;
            left:101%;
            top:52%;
        }

        .planet-5 {
            position: absolute;
            left:100%;
            top:54%;
        }

        .planet-6 {
            position: absolute;
            left:100%;
            top:52%;
        }

        .planet_img_1 { width: 25px; }
        .planet_img_2 { width: 30px; }
        .planet_img_3 { width: 20px; }
        .planet_img_4 { width: 30px; }
        .planet_img_5 { width: 10px; }
        .planet_img_6 { width: 50px; }

        .asteroid {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(0deg);
            animation: orbit 80s linear infinite;
            width: 100%;
            height: auto;
        }

        @keyframes orbit {
            from { transform: translate(-50%, -50%) rotate(0deg); }
            to { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .asteroid-img {
            width: 100%;
            height: auto;
        }

        /* Planets with different starting positions (initial rotation) */
        .orbit-1 .planet { animation: orbit 20s linear infinite; transform-origin: -198px 0; }
        .orbit-2 .planet { animation: orbit 30s linear infinite; transform-origin: -245px 0; }
        .orbit-3 .planet { animation: orbit 40s linear infinite; transform-origin: -300px 0; }
        .orbit-4 .planet { animation: orbit 55s linear infinite; transform-origin: -400px 0; }
        .orbit-5 .planet { animation: orbit 10s linear infinite; transform-origin: -112px 0; }
        .orbit-6 .planet { animation: orbit 70s linear infinite; transform-origin: -660px 0; }
        
        .solar_map_left{
            box-sizing: border-box;
            position: absolute;
            display: flex;
            justify-content: center;
            top: 0;
            left:0;
            width: 20%;
            height: 100%;
            color: white;
            background: linear-gradient(90deg, rgba(217, 217, 217, 0.24) 0%, rgba(115, 115, 115, 0.00) 100%);
            z-index: 20;
            text-align: center;
        }
        .solar_map_right{
            box-sizing: border-box;
            position: absolute;
            display: flex;
            justify-content: center;
            top: 0;
            right: 0;
            width: 25%;
            height: 100%;
            color: white;
            background: linear-gradient(270deg, rgba(217, 217, 217, 0.24) 0%, rgba(115, 115, 115, 0.00) 100%);
            z-index: 20;
            text-align: center;
        }
        .elipse_container_planet{
            position: absolute;
            display: flex;
            justify-content: center;
            align-content: center;
            top: 140px;
            background: linear-gradient(to top, #280045, #8300ba);
            border-radius: 50%;
            width: 210px;
            height: 210px;
            padding:10px;
        }
        .btnleft{
            position: relative;
            top: 340px;
            width: 100px;
            height: 20px;
            margin: 10px;
            background: linear-gradient(to left, #280045, #8300ba);
            color: azure;
            border-style: none;
            border-radius: 10px;
            z-index: 20;
        }
        .btnright{
            position: relative;
            top: 340px;
            width: 100px;
            height: 20px;
            margin: 10px;
            background: linear-gradient(to right, #280045, #8300ba);
            color: azure;
            border-style: none;
            border-radius: 10px;
            z-index: 20;
        }
        .hyperlink{
            position: relative;
            width: fit-content;
            height: fit-content;
            margin: 10px;
            padding: 10px;
            background: linear-gradient(to right, #280045, #8300ba);
            color: azure;
            border-style: none;
            border-radius: 20px;
            text-decoration: none;
        }
        .hyperlink:hover{
            background: linear-gradient(to left, #280045, #8300ba);
        }
        .btnleft:hover{
            background: linear-gradient(to right, #280045, #8300ba);
        }
        .btnright:hover{
            background: linear-gradient(to left, #280045, #8300ba);
        }
        .property-card {
            width: 100%;
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
        .properties-card-container{
            width: 100%; 
            height:100%; 
            display: flex; 
            box-sizing: border-box; 
            position: absolute; 
            justify-content: center; 
            text-align: center;
            overflow-y:scroll;
            overflow:hidden; 
            top:20%; 
            right:5%;
            bottom:0;
        }
        .propertes-card-container::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }
        #property_list{
            width:80%;
        }
        .pin_hyperlink{
            width: fit-content; height: 25px; padding-left: 10px; padding-right: 10px;border-radius: 20px; background-color: #4F378B; color: azure; border-style: none; position:relative; top:90px;
        }
        .pin_hyperlink:hover{
            background-color:mediumslateblue;
        }
</style>
</head>
<body>
    <img style="width: 100%; position: fixed;" src="/images/backgrounds/subtle_stars.png" alt="">
    <section id="top">
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
    <div class="solar_map_left">
        <h1 style="margin-top: 100px; width: 100%; font-size: 30px;" id="planet_name"></h1>
        <div class="elipse_container_planet">
            <img style="width: 140%; height: 100%;" id="planet_img" alt="">
        </div>
        <div style="position:relative; top :30px;width: 100%; display: flex; box-sizing: border-box; position: absolute; justify-content: center;">
            <button onclick="left()" class="btnleft">Previous</button>
            <button onclick="right()" class="btnright">Next</button>
        </div>
        <div style="width: 100%; height: fit-content; display: flex; box-sizing: border-box; position: absolute; justify-content: center; text-align: center; top:400px; flex-wrap: wrap;">
            <p id="region_description"></p>
            <a class="hyperlink" href="#map">View map</a>
        </div>
    </div>
    <div class="solar-system" id="solar-system">
        <div class="sun">
            <img style="width: 160px; position: relative; left: -30%; top: -10%;" src="../images/planets/Sun.png" alt="">
        </div>
        
        <div class="orbit orbit-1">
            <div class="planet planet-1"><img class="planet_img_1" src="../images/planets/Venus.png" alt=""></div>
        </div>
        
        <div class="orbit orbit-2">
            <div class="planet planet-2"><img class="planet_img_2" src="../images/planets/Earth.png" alt=""></div>
        </div>
        
        <div class="orbit orbit-3">
            <div class="planet planet-3"><img class="planet_img_3" src="../images/planets/Mars.png" alt=""></div>
        </div>

        <div class="orbit orbit-4">
            <div class="planet planet-4"><img class="planet_img_4" src="../images/planets/Arcadia.png" alt=""></div>
        </div>
        
        <div class="orbit orbit-5">
            <div class="planet planet-5"><img class="planet_img_5" src="../images/planets/Mercury.png" alt=""></div>
        </div>

        <div class="orbit asteroid-belt">
            <div class="asteroid asteroid-1"><img class="asteroid-img" src="../images/planets/Asteroids.gif" alt=""></div>
        </div>
        
        <div class="orbit orbit-6">
            <div class="planet planet-6"><img class="planet_img_6" src="../images/planets/Jupiter.png" alt=""></div>
        </div>
    </div>
    <div class="solar_map_right">
        <h1 style="position:absolute;top: 10%; width: 100%; font-size: 30px;" id="right_planet_name"></h1>
        <div class="properties-card-container" >
            <div id="property_list"></div>
        </div>
    </div>
    </section>
    <section>
    <div style="width:100%; height:90%; position:absolute; top:0;">
        <div id="map" style="width:100%; height:100%; position:absolute; top:0;">
        </div>
        <div class="footer" style="position:fixed; bottom:0; left:0;">
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
                    <a class="nav_link" href="#top">Back to top</a>
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
    <script>
        // Add event listener for mouse wheel
        let currentPlanet = 0; // Track the current planet index

        const planetData = [
            { name: "Mercury", img: "../images/planets/Mercury.png", description: "Currently uninhabitable due to extreme temperatures and harsh conditions, Mercury Estates offers investors a glimpse into potential future developments. This planet remains a frontier for innovative housing solutions, awaiting breakthroughs in environmental technology."},
            { name: "Venus", img: "../images/planets/Venus.png", description: "Experience luxury living at Venus View Properties, where the atmosphere is as inviting as the stunning landscapes. With properties boasting breathtaking views, this planet is perfect for homeowners seeking tranquility amidst beauty."},
            { name: "Earth", img: "../images/planets/Earth.png", description: "At Earthly Abodes, we pride ourselves on providing a diverse range of homes that reflect our commitment to sustainability. Ideal for families and eco-conscious individuals, our listings span vibrant urban areas to serene rural escapes."},
            { name: "Mars", img: "../images/planets/Mars.png", description: "Discover the potential of Mars Realty, where groundbreaking properties await visionary buyers. Ideal for those looking to invest in the future of real estate, Mars offers unique opportunities in a developing market."},
            { name: "Arcadia", img: "../images/planets/Arcadia.png", description: "Arcadia Haven Homes specializes in idyllic retreats and scenic landscapes, perfect for those looking to escape the hustle and bustle. Our properties are designed for relaxation and leisure, making them ideal for vacationers and second-home buyers."},
            { name: "Jupiter", img: "../images/planets/Jupiter.png", description: "Currently uninhabitable due to extreme temperatures, pressures and harsh conditions, Jupiter as of yet awaits breakthroughs in environmental technology. like being able to live in jupiters clouds without succuming to the intense gravity and experience planet fall."},
            { name: "Saturn", img: "../images/planets/Saturn.png", description: "Currently uninhabitable due to extreme temperatures, pressures, and harsh conditions. Saturn, known for its stunning ring system and gaseous composition, presents an environment that is incredibly hostile to life as we know it, making it a fascinating but inhospitable world." },
            { name: "Uranus", img: "../images/planets/Uranus.png", description: "Currently uninhabitable due to extreme temperatures, pressures, and harsh conditions. Uranus, the icy giant, possesses a unique tilt that gives it extreme seasonal variations and has a chilling atmosphere filled with methane, contributing to its blue-green hue, yet remains devoid of any known life." },
            { name: "Neptune", img: "../images/planets/Neptune.png", description: "Currently uninhabitable due to extreme temperatures, pressures, and harsh conditions. Neptune, known for its deep blue color and fierce storms, is located at the outer edge of the solar system, where the temperatures plunge to frigid levels, creating an environment that is inhospitable to life." 
},
            { name: "Pluto", img: "../images/planets/Pluto.png", description: "Currently uninhabitable due to extreme temperatures, lack of pressure and harsh cosmic radiation, pluto as of yet awaits scientific breakthrough due to the atmosphere snowing down on the planet as it cools down at its farthest point from the sun"}
        ];

        function updatePlanetInfo() {
            const planetinfo = planetData[currentPlanet];
            document.getElementById('planet_name').innerText = planetinfo.name;
            document.getElementById('planet_img').src = planetinfo.img;
            document.getElementById('region_description').innerText = planetinfo.description;
        }

        function left() {
            currentPlanet = (currentPlanet > 0) ? currentPlanet - 1 : planetData.length - 1;
            updatePlanetInfo();
            displayPropertiesForPlanet(planetData[currentPlanet].name);
        }

        function right() {
            currentPlanet = (currentPlanet < planetData.length - 1) ? currentPlanet + 1 : 0;
            updatePlanetInfo();
            displayPropertiesForPlanet(planetData[currentPlanet].name);
        }

        function start(){
            currentPlanet = 0;
            updatePlanetInfo();
            displayPropertiesForPlanet(planetData[currentPlanet].name);
        }

        start();

        // Initialize with the first planet's info
        updatePlanetInfo();

        var map;
        var geocoder;
        var markers = [];

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 37.7749, lng: -122.4194 }, // Centered on San Francisco
                zoom: 5
            });

            geocoder = new google.maps.Geocoder();

            // Dynamically generated Earth locations from PHP
            var earthLocations = <?php echo $json_properties; ?>;

            console.log(earthLocations); // Log the locations

            // Add markers with a delay
            addMarkersWithDelay(earthLocations);
        }

        async function addMarkersWithDelay(locations) {
            for (const location of locations) {
                console.log(location.address); // Log the address for debugging
                await new Promise(resolve => setTimeout(resolve, 300)); // Delay of 300ms
                await addMarkerByAddress(location); // Ensure this function returns a promise
            }
        }

        // Function to add a marker to the map using address
        async function addMarkerByAddress(location) {
            return new Promise((resolve) => {
                geocoder.geocode({ address: location.address }, function(results, status) {
                    if (status === "OK") {
                        var markerPosition = results[0].geometry.location;

                        // Determine the icon properties based on whether it's an Area 51 property
                        var iconOptions = {
                            url: '/images/icons/marker.png', // Path to your marker image
                            anchor: new google.maps.Point(10, 20), // Default anchor
                            scaledSize: new google.maps.Size(30, 40)
                        };

                        // Check if the property is an Area 51 property
                        if (location.title.toLowerCase().includes("area 51")) {
                            iconOptions.anchor = new google.maps.Point(10, 30); // Adjust anchor for Area 51 markers
                        }

                        // Create a marker with the defined icon
                        var marker = new google.maps.Marker({
                            map: map,
                            position: markerPosition,
                            icon: iconOptions
                        });

                        // Info window with property details
                        var infowindow = new google.maps.InfoWindow({
                            content: `
                                <div style="width: 400px; height:550px;background: linear-gradient(to top, #280045, #8300ba); text-align:center; position:relative; overflow:hidden; color:white;">
                                    <div style="width: 100%; height: fit-content;">
                                        <div class="property_title">
                                            <div style="width:100%; height:100px;">
                                                <p style="position:fixed; left:20px; top:50px;">${location.title}</p>
                                                <p style="position:fixed; right:50px; top:50px;">$${location.price}</p>
                                            </div>
                                        </div>
                                        <div style="width: 100%; height: 10px;">
                                            <p style="width: 80px; font-size: 18px; position:fixed; top:100px; left:20px;">Country:</p>
                                            <p style="width: 70px; font-size: 15px; position:fixed; top:100px; left:100px;">${location.country}</p>
                                            <div style="background: linear-gradient(to top, #280045, #8300ba); height: 80px; width: 80px; border-radius: 50%; position:fixed; top:60px; left:41%; display:flex; align-items:center;justify-content:center; padding:10px;">
                                                <img style="width: 100px; height:auto; border-radius: 50%;" src="/images/planets/${location.planet}.png">
                                            </div>
                                            <p style="width: 70px; font-size: 18px; position:fixed; top:100px; right:90px;">State:</p>
                                            <p style="width: 100px; font-size: 15px; position:fixed; top:100px; right:10px;">${location.state}</p>
                                        </div>
                                        <p>${location.address}</p>
                                        <div style="width:100%;">
                                            <div style="background: radial-gradient(circle, #9501ff 0%, #59007f 42%,  #000000 100%); border-radius: 50%; width: 220px; height: 220px; position:relative; left:22%; align-content:center; justify-content:center;">
                                                <img style="width: 190px; height:190px; border-radius: 50%; border-color: #59007f; border-width: 3px; border-style: solid; position:relative;" src="/images/properties/${location.img}" alt="">
                                            </div>
                                            <div style="background: linear-gradient(to top, #280045, #8300ba); height: 70px; width: 70px; border-radius: 50%; margin: 10px; position:fixed; top:200px; left :30px;">
                                                <img class="bathrooms" src="/images/icons/bathroom.svg" alt="">
                                            </div>
                                            <div style="background: linear-gradient(to top, #280045, #8300ba); height: 70px; width: 70px; border-radius: 50%; margin: 10px; position:fixed; top:200px; right :30px;">
                                                <img class="bedrooms" src="/images/icons/bedroom.svg" alt="">
                                            </div>
                                            <div style="background: linear-gradient(to top, #280045, #8300ba); height: 70px; width: 70px; border-radius: 50%; margin: 10px; position:fixed; top:420px; right :41%;">
                                                <img class="kitchens" src="/images/icons/kitchen.svg" alt="">
                                            </div>
                                            <div style="background: linear-gradient(to top, #280045, #8300ba); height: 70px; width: 70px; border-radius: 50%; margin: 10px; position:fixed; top:350px; left :10%">
                                                <img class="livingrooms" src="/images/icons/livingroom.svg" alt="">
                                            </div>
                                            <div style="background: linear-gradient(to top, #280045, #8300ba); height: 70px; width: 70px; border-radius: 50%; margin: 10px; position:fixed; top:350px; right :10%;">
                                                <img class="garages" src="/images/icons/garage.svg" alt="">
                                            </div>
                                            <button class="pin_hyperlink" onclick="visit_pty(this)" id="${location.title}">Visit ${location.title}</button>
                                        </div>
                                        <div style="position:relative; top:90px;">
                                            <p style="width:80%; margin-left:10%;">${location.description}</p>
                                        </div>
                                    </div>
                                </div>
                            `
                        });

                        marker.addListener('click', function() {
                            infowindow.open(map, marker);
                        });

                        markers.push(marker);
                        resolve(); // Resolve the promise
                    } else {
                        console.error("Geocode was not successful for the following reason: " + status);
                        resolve(); // Resolve even if there's an error to continue processing
                    }
                });
            });
        }

        // Load the map when the window is ready
        window.onload = initMap;

        function visit_pty(button) {
            const propertyName = button.id; // Get the property name from button's id

                // Encode the property name to handle spaces and special characters
                const encodedPropertyName = encodeURIComponent(propertyName);

                // Debug: Print the final URL to ensure it's correct
                console.log(`/Pages/property_description.php?name=${encodedPropertyName}`);

                // Redirect to property_description.php with the encoded property name
                window.location.href = `/Pages/property_description.php?name=${encodedPropertyName}`;
        }


        // Function to display properties for a specific planet
            function displayPropertiesForPlanet(planet) {
                var properties = <?php echo json_encode($properties); ?>;
                const planetNameElement = document.getElementById('right_planet_name');
                const planetDescriptionElement = document.getElementById('property_list');


                // Clear previous properties
                planetDescriptionElement.innerHTML = '';
                
                // Populate planet name
                planetNameElement.textContent = planet;
                
                // Initialize an empty string for the property list
                let liststring = '';

                // Create list items for each property
                properties.forEach(property => {
                    if (property.planet === planet) { // Check if the property's planet matches the selected planet
                        liststring +=`  <a style="color: inherit;text-decoration: none;" href="property_description.php?name=${property.title}">
                                            <div class="property-card">
                                                <img class="property-img" src="../images/properties/${property.img}" alt="${property.title}">
                                                <div>
                                                    <h2>${property.title}</h2>
                                                    <p>${property.city}, ${property.state}, ${property.country}, ${property.planet}</p>
                                                    <p>${property.price} Credits</p>
                                                </div>
                                            </div>
                                        </a>`
                                        }
                });
                if(liststring===''){
                    liststring='<p>No Properties currently on the planet you are looking for try selecting another planet or try browzing the Map below. just click viewmap and youll see the map with all the properties</p>'}
                // Set the inner HTML of the description element to the constructed list string
                planetDescriptionElement.innerHTML = liststring; 
            }
    </script>
</body>
</html>
