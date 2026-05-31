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

// Fetch distinct values for the filters
$filterFields = ['bedrooms', 'bathrooms', 'livingrooms', 'kitchens', 'garages', 'planet', 'country', 'state', 'property_type'];
$filterOptions = [];

foreach ($filterFields as $field) {
    $result = $conn->query("SELECT DISTINCT $field FROM properties WHERE $field IS NOT NULL");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $filterOptions[$field][] = $row[$field];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data (JSON)
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    // Debug: log the received data
    error_log("Received data: " . print_r($data, true));

    // Get values from the received data
    $bedrooms = isset($data['bedrooms']) ? htmlspecialchars($data['bedrooms']) : '';
    $bathrooms = isset($data['bathrooms']) ? htmlspecialchars($data['bathrooms']) : '';
    $livingRooms = isset($data['livingRooms']) ? htmlspecialchars($data['livingRooms']) : '';
    $kitchens = isset($data['kitchens']) ? htmlspecialchars($data['kitchens']) : '';
    $garages = isset($data['garages']) ? htmlspecialchars($data['garages']) : '';
    $planet = isset($data['planet']) ? htmlspecialchars($data['planet']) : '';
    $country = isset($data['country']) ? htmlspecialchars($data['country']) : '';
    $state = isset($data['state']) ? htmlspecialchars($data['state']) : '';
    $propertyType = isset($data['propertyType']) ? htmlspecialchars($data['propertyType']) : '';

    // Build SQL query
    $sql = "SELECT * FROM properties WHERE 1=1";

    if ($bedrooms) {
        $sql .= " AND bedrooms = '$bedrooms'";
    }
    if ($bathrooms) {
        $sql .= " AND bathrooms = '$bathrooms'";
    }
    if ($livingRooms) {
        $sql .= " AND livingrooms = '$livingRooms'";
    }
    if ($kitchens) {
        $sql .= " AND kitchens = '$kitchens'";
    }
    if ($garages) {
        $sql .= " AND garages = '$garages'";
    }
    if ($planet) {
        $sql .= " AND planet = '$planet'";
    }
    if ($country) {
        $sql .= " AND country = '$country'";
    }
    if ($state) {
        $sql .= " AND state = '$state'";
    }
    if ($propertyType) {
        $sql .= " AND property_type = '$propertyType'";
    }

    // Debug: log the SQL query
    error_log("SQL Query: " . $sql);

    // Execute query and get results
    $result = $conn->query($sql);
    $properties = [];

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $properties[] = [
                    'property_name' => $row['property_name'],
                    'price' => $row['price'],
                    'country' => $row['country'],
                    'state' => $row['state'],
                    'planet' => $row['planet'],
                    'img' => $row['img'],
                    'description' => $row['description'] // Include the description
                ]; // Store each property
            }
        } else {
            // No properties found
            error_log("No properties found.");
        }
    } else {
        // SQL query failed
        error_log("SQL Error: " . $conn->error);
    }

    // Return response as JSON
    header('Content-Type: application/json');
    echo json_encode($properties);
    exit; // Ensure no further output is sent
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="/css/main_styles.css">
    <link rel="stylesheet" href="/css/property_page.css">
    <link rel="stylesheet" href="/css/landing_page.css">
    <style>
        html, body {
            scroll-snap-type: none;
        }
    </style>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="Background Image"> 
    <section>
        <div style="position:relative; top:0; left:0; width:100%;height:80px;">
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
        </div>
        <div class="filter_container">
            <div style=" position:relative;width:100%; padding-bottom:20px;align-content:center;">
                <h1>Filter Properties</h1>
                <div style=" width:100%; display:flex; flex-wrap:wrap;justify-content:center; align-items:center;">
                    <?php
                    // Initialize a counter for generating unique IDs
                    $selectCounter = 1;

                    // Loop through the filters and create select elements with unique IDs
                    foreach ($filterFields as $field) {
                        if (!empty($filterOptions[$field])) {
                            echo "<select id='$field' name='$field' class='filter_select'>";
                            echo "<option value=''>Select $field</option>";
                            
                            $optionCounter = 1; // Initialize option counter
                            foreach ($filterOptions[$field] as $option) {
                                echo "<option value='$option'>$option</option>"; // Use the option value directly
                                $optionCounter++; // Increment option counter
                            }
                            echo "</select><br>";
                            
                            $selectCounter++; // Increment select ID counter
                        }
                    }
                    ?>
                </div>    
            </div>

            <button class="filter-link" onclick="fetchProperties()">Search Properties</button>
        </div>
        <div class="pty_container">
        
            <div style=" position:relative;width:90%;height:auto; align-content:center;text-align:center;">
                <h1 style="font-size:40px;">Listings</h1>
                <p style="font-size:21px;"> Browse your dream property on your favourite planet. What should you consider? there are a multitude of tourist attractions that you would be able to visit, we have a special selection of estates that are central to all of the most important tourist attractions such as the venusian hot springs that will bubble your sorrows away, the martian gravity allows for the first ever flight of man without any propulsion so you wont even dream your flying youd actually be able to. and as for Arcadia the cradle of innovation and technology and the perfect getaway for a tech enthusiast with its luxurious loft apartments to the zero gravity lazer tag. The choice is yours.... feel free to browse the available properties below and don't forget to filter if you are more particular.</p>
            </div>
            <div id="properties" style="display: flex; flex-wrap: wrap; gap: 10px;text-align: center;position: relative;top: 90px;color:azure;width:1400px; height:fit-content;align-content:center; justify-content:space-between;">
            </div>
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
    <section/>        
<script>
    function fetchProperties() {
        const propertiesDiv = document.getElementById("properties");
        propertiesDiv.innerHTML = ""; // Clear previous results

        // Gather filter values from inputs
        const bedrooms = document.getElementById("bedrooms").value;
        const bathrooms = document.getElementById("bathrooms").value;
        const livingRooms = document.getElementById("livingrooms").value;
        const kitchens = document.getElementById("kitchens").value;
        const garages = document.getElementById("garages").value;
        const planet = document.getElementById("planet").value;
        const country = document.getElementById("country").value;
        const state = document.getElementById("state").value;
        const propertyType = document.getElementById("property_type").value;

        // Prepare data to send to PHP
        const data = {
            bedrooms: bedrooms,
            bathrooms: bathrooms,
            livingRooms: livingRooms,
            kitchens: kitchens,
            garages: garages,
            planet: planet,
            country: country,
            state: state,
            propertyType: propertyType
        };

        // Send data to the server via POST request
        fetch('/Pages/properties.php', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(properties => {
            if (properties.length === 0) {
                propertiesDiv.innerHTML = "<p>No properties found.</p>";
            } else {
                let counter = 0; // Initialize the counter

                // Iterate over each property and display it
                properties.forEach(property => {
                    let cardClass = "";
                    
                    if (counter % 3 === 0) {
                        cardClass = "left_"; // Left card
                    } else if (counter % 3 === 1) {
                        cardClass = ""; // Center card
                    } else {
                        cardClass = "right_"; // Right card
                    }

                    const propertyDiv = document.createElement("div");
                    propertyDiv.className = `property ${cardClass}`; // Add the card class
                    propertyDiv.innerHTML = `
    <div class="${cardClass}card">
        <div class="${cardClass}top_half">
            <div class="${cardClass}property_title">
                <div style="display: flex; flex-wrap: wrap;">
                    <p class="${cardClass}name">${property.property_name}</p>
                    <p class="${cardClass}price">$${parseFloat(property.price).toLocaleString()}</p>
                </div>
            </div>
            <div class="${cardClass}region">
                <p style="width: 80px; font-size: 18px;">Country:</p>
                <p style="width: 70px; font-size: 15px;">${property.country}</p>
                <div class="${cardClass}region_elipse">
                    <img style="" src="/images/planets/${property.planet}.png" alt="">
                </div>
                <p style="width: 70px; font-size: 18px;">State:</p>
                <p style="width: 100px; font-size: 15px;">${property.state}</p>
            </div>
            <div class="${cardClass}center">
                <div class="${cardClass}elipsebacking">
                    <img class="${cardClass}main_img" src="/images/properties/${property.img}" alt="">
                </div>
                <div class="${cardClass}bottom_half">
                    <div class="${cardClass}description_elipse_outer">
                        <img class="${cardClass}bathrooms" src="../images/icons/bathroom.svg" alt="">
                    </div>
                    <div class="${cardClass}description_elipse_inner">
                        <img class="${cardClass}bedrooms" src="../images/icons/bedroom.svg" alt="">
                    </div>
                    <div class="${cardClass}description_elipse_center">
                        <img class="${cardClass}kitchens" src="../images/icons/kitchen.svg" alt="">
                    </div>
                    <div class="${cardClass}description_elipse_inner">
                        <img class="${cardClass}livingrooms" src="../images/icons/livingroom.svg" alt="">
                    </div>
                    <div class="${cardClass}description_elipse_outer">
                        <img class="${cardClass}garages" src="../images/icons/garage.svg" alt="">
                    </div>
                </div>
                <a class="filter-link" href="/Pages/property_description.php?name=${property.property_name}">Visit ${property.property_name}</a>
            </div>
            <div class="${cardClass}">
                <p style="font-size:18px;">${property.description}</p>
            </div>
        </div>
    </div>
                    `;
                    propertiesDiv.appendChild(propertyDiv);
                    counter++; // Increment the counter
                });
            }
            
            // Clear the filter fields
            clearFilters();
        })
        .catch(error => {
            propertiesDiv.innerHTML = "<p>An error occurred while fetching properties...</p>";
            console.error(error);
        });
    }

    function clearFilters() {
        // Reset all filter fields to their default values
        document.getElementById("bedrooms").value = "";
        document.getElementById("bathrooms").value = "";
        document.getElementById("livingrooms").value = "";
        document.getElementById("kitchens").value = "";
        document.getElementById("garages").value = "";
        document.getElementById("planet").value = "";
        document.getElementById("country").value = "";
        document.getElementById("state").value = "";
        document.getElementById("property_type").value = "";
    }
    fetchProperties(); // Call after setting user data
</script>

</body>
</html>
