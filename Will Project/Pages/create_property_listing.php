<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assume a database connection is already established
$conn = new mysqli("sql311.infinityfree.com", "if0_37309654", "Casperw777", "if0_37309654_RealHomeDataBase");

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
    <title>Create Listing</title>
    <link rel="stylesheet" href="/css/main_styles.css">
    <link rel="stylesheet" href="/css/property_page.css">
    <link rel="stylesheet" href="/css/landing_page.css">
    <style>
        .property-form-container {
            width: 80%;
            margin: 0 auto;
            padding-left: 30px;
            padding-top: 10px;
            position: absolute;
            top: 100px;
            background: linear-gradient(180deg, rgba(217, 217, 217, 0.48) 0%, rgba(217, 217, 217, 0.24) 50%, rgba(115, 115, 115, 0.04) 100%);
            border-radius: 35px;
        }

        #property-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .form-group {
            flex: 1 1 48%; /* Each form-group will take up almost half (48%) */
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 12px;
        }

        .form-group input, .form-group textarea {
            width: 95%;
            height: 20px;
            padding: 8px;
            box-sizing: border-box;
        }

        .form-group textarea {
            height: 50px;
            resize: vertical;
        }

        /* Ensure full width for file upload and submit button */
        .form-group.full-width {
            flex: 1 1 100%;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            color: green;
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
        textarea{    
            width:80%;
            border-radius:10px;
            opacity:0.5;
        }
    </style>
</head>
<body>
    <img style="width: 100%; position: fixed;" src="/images/backgrounds/subtle_stars.png" alt="">
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

        <form class="property-form-container" id="property-form" action="/Pages/add_property.php" method="POST" enctype="multipart/form-data">
            <!-- Property Name -->
            <div class="form-group">
                <label for="property_name">Property Name:</label>
                <input type="text" id="property_name" name="property_name" required />
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required />
            </div>

            <!-- City -->
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" required />
            </div>

            <!-- State -->
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" required />
            </div>

            <!-- Country -->
            <div class="form-group">
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" required />
            </div>

            <!-- Planet -->
            <div class="form-group">
                <label for="planet">Planet:</label>
                <select id="planet" name="planet" required>
                    <option value="Mercury">Mercury</option>
                    <option value="Venus">Venus</option>
                    <option value="Earth">Earth</option>
                    <option value="Mars">Mars</option>
                    <option value="Jupiter">Jupiter</option>
                    <option value="Saturn">Saturn</option>
                    <option value="Uranus">Uranus</option>
                    <option value="Neptune">Neptune</option>
                    <option value="Pluto">Pluto</option>
                </select>
            </div>

            <!-- Price -->
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required />
            </div>

            <!-- Property Type -->
            <div class="form-group">
                <label for="property_type">Property Type:</label>
                <input type="text" id="property_type" name="property_type" required />
            </div>

            <!-- Bedrooms -->
            <div class="form-group">
                <label for="bedrooms">Bedrooms:</label>
                <input type="number" id="bedrooms" name="bedrooms" required />
            </div>

            <!-- Bathrooms -->
            <div class="form-group">
                <label for="bathrooms">Bathrooms:</label>
                <input type="number" id="bathrooms" name="bathrooms" required />
            </div>

            <!-- Living Rooms -->
            <div class="form-group">
                <label for="livingrooms">Living Rooms:</label>
                <input type="number" id="livingrooms" name="livingrooms" required />
            </div>

            <!-- Kitchens -->
            <div class="form-group">
                <label for="kitchens">Kitchens:</label>
                <input type="number" id="kitchens" name="kitchens" required />
            </div>

            <!-- Garages -->
            <div class="form-group">
                <label for="garages">Garages:</label>
                <input type="number" id="garages" name="garages" required />
            </div>

            <!-- Image Upload--> 
            <div style="display:flex; height:70px;">
                <div style="display:flex; height:70px;">
                    <div style="width:fit-content;">
                        <img id="image">
                    </div>
                    <div style="width:400px; height:fit-content;">
                        <label for="img">Select image:</label>
                        <input type="file" id="img" name="img" accept="image/*" required />
                    </div>
                </div>    
            </div>

            <!-- Description -->
            <div class="form-group full-width">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <!-- Agent -->
            <div class="form-group full-width">
                <input type="hidden" id="agent" name="agent" value="<?php echo $_SESSION['user']; ?>" required />
            </div>

            <!-- Submit Button -->
            <div class="form-group full-width">
            <?php if($_SESSION['user'] !== 'guest'):?>
                <button type="submit" value="Add Property">Submit</button>
            <?php else:?>
                <p style="font-size:20px; color:azure;">Log in to List a property</p>
            <?php endif;?>
            </div>
        </form>

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
        document.getElementById('img').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const image = document.getElementById('image');
                    image.src = e.target.result;
                    image.style.maxWidth = '100px';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>
</html>
