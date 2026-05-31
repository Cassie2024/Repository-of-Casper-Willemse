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

// Initialize response array
$response = [];
// Check if the username variable is set
if (!isset($_SESSION['user'])) {
    // Set guest session data
    $_SESSION['email'] = 'realhome@realestate.co.za';
    $_SESSION['user'] = 'guest';
    $_SESSION['account_type'] = 'guest';
    $_SESSION['img'] = 'guest.png';
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Landing page</title>
    <link rel="stylesheet" href="css/landing_page.css">
    <link rel="stylesheet" href="css/main_styles.css">
    <link href="https://cesium.com/downloads/cesiumjs/releases/1.92/Build/Cesium/Widgets/widgets.css" rel="stylesheet">
    <script src="https://cesium.com/downloads/cesiumjs/releases/1.92/Build/Cesium/Cesium.js"></script>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="Background Image">
    <!-- Welcome Page -->
    <section>
        <div class="main_page">
            <!-- Navbar -->
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
            <!-- main content of welcome page -->
            <div class="section1_body">
                <p>Welcome </p>
                <p>to RealHome</p>
                <p><?php echo $_SESSION['user']?></p>
                
                <div class="section1_mail">
                    <h6 style="position: absolute; left: 10%;">Your dream our passion</h6>
                    <a href="/Pages/properties.php" class="filter-link">Start Browsing today</a>
                    <h6 style="position: absolute; right: 10%;">RealHome_realestate@gmail.com</h6>
                </div>
            </div>
        </div>
    </section>
    <!-- featured properties on every planet -->
    <section>
        <div class="left">
            <div class="content_header">
                <h3>Featured Properties</h3>
            </div>
            <div class="main_content">
                <div class="opaque_panel">
                    <div class="content_text">
                        <p>RealHome has many beautiful homes across many of the inner planets, including the newly built ark of humanity called Arcadia.</p>
                        <a href="/Pages/properties.php" class="filter-link">Explore</a>
                        <div style="height: 20px;">
                        </div>
                    </div>
                    <div class="content_left">
                        <div class="header_container">
                            <p style="font-size: 20px;" id="property_location">Location: Earth ~ Africa</p>
                        </div>
                        <div class="elipse_container_1">
                            <img id="region_img" class="region_img" alt="Earth Image">
                        </div>
                    </div>
                    <div class="content_right">
                        <div class="content_text">
                            <h1>Description</h1>
                            <p id="property_description"></p>
                        </div>
                    </div>
                </div>
            </div>    
        </div>
        <div class="right">
            <div class="content_text">
                <h3 id="property_name">Modern Villa</h3>
                <p id="property_price">R2000000</p>
            </div>
            <div class="sub_content">
                <div class="elipse1">
                    <img id="property_img" alt="">
                </div>
            </div>
            <div class="sub_content">
                <div class="description_icon_1"><button class="icon_button"><img class="button_img" src="../images/icons/bedroom.svg" alt=""></button></div>
                <div class="description_icon_2"><button class="icon_button"><img class="button_img" src="../images/icons/bathroom.svg" alt=""></button></div>
                <div class="description_icon_3"><button class="icon_button"><img class="button_img" src="../images/icons/livingroom.svg" alt=""></button></div>
                <div class="description_icon_4"><button class="icon_button"><img class="button_img" src="../images/icons/garage.svg" alt=""></button></div>
                <div class="description_icon_5"><button class="icon_button"><img class="button_img" src="../images/icons/kitchen.svg" alt=""></button></div>
                <div class="description_icon_6"><button class="icon_button"><img class="button_img" src="../images/icons/finance.svg" alt=""></button></div>
            </div>
            <div class="content_text">
                <p style="margin-top: 100px;">Here at Real Home we have a beautiful selection of estates. We prepared a seclection of breathtaking properties you can browse, click explore to go to properties and find your dream estate</p>
            </div>
            </div>
        </div>
    </section>
    <!-- About us sections -->

    <section id="about_us">
        <div class="opaque_panel_about_regions">
                
        </div>
        <div style="position:relative; width:60%; left:0;display:flex; flex-direction:row;">
            <div style="position:relative; width:60%; left:0;">
                <div style="display:flex; text-align:center; left:0; font-size:20px; width:100%;">
                    <div class="about_us_container">
                        <div class="about_main_content">
                            <h1 style="width: 100%; font-size: 40px;">About Us</h1>
                        </div>
                        <p style="width:100%;">RealHome is Pioneering advancedments in the space industry,interplanetary Real Estate has started to boom due to a surge of population growth and advancements in habitation in space. We will be supporting missions that push the boundaries of exploration. 
                            Our initiatives to bring home closer to the stars and the focus on developing a sustainable growth of living amongst the stars. 
                            This expidition is fostering advancements like Asteroid Mining and Lunar Drilling. 
                            Collaboration of space endeavours have brought us closer as a species through collective interest of space.
                        </p>
                        <H1>What we do</H1>
                        <p> We are a Team of Astroneers that seek to expand the human race beyond the earth,
                            we have always dream of living amongst the stars it is time we realize that dream.</p>   
                    </div>    
                </div>
            </div>
            <div style="position:relative; width:40%; right:0;display:flex;text-align:center; flex-direction:column;">
                <h1>RealHome: Where Dreams Become Reality</h1>
                <div class="elipse_prof">
                            <img class="elipse_prof_img_container" src="images/users/agent3.jpg" alt="">
                </div> 
                <p>
                    Here at RealHome, we do what no other real estate agency would dare. We value creativity, innovation, and a deep commitment to making your homeownership journey unique. With us, your vision becomes our mission.
                </p>
            </div>
        </div>

        <div class="right">
            <div class="content_header">
                <p>What we offer at Real home</p>
            </div>
        <div class="main_content">
            <div class="about_us_container_2">
                <div class="elipse_Large">
                <!--Gif-->
                    <img class="elipse_large_img" id="image-slider" alt="">
                </div>
                <div class="content_text">
                    <p id="offerings_text"></p>
                </div>
            </div>    
        </div>
        </div>
    </section>

    <section>
        <div class="opaque_panel_region">

        </div>
        <div style="box-sizing: border-box;top: 0;width: 100%;height: 50%;color: white; display: flex; flex-wrap: wrap;">
            <div class="left">
               <div class="content_header">
                    <h1>About our regions</h1>
               </div>
               <div class="content_text">
                    <p>RealHome is the key to your dream estate, we are located across the inner part of the solar system. We offer exeptional properties on Mars, Venus, Earth and the state of the art ark of humanity called Arcadia. Earth remains the hub of diversity, nature and beauty, while Arcadia promises an enriching and futuristic lifestyle, whether you seek the tranquility and peace of Mars and Earth and the excitement and wonder of Arcadia or the Beauty and Elagance of Venus. RealHome will make your dream come true.  </p>
               </div>
            </div>
            <div class="right">
                <div class="content_text">
                    <div style="width: 100%;height: 50%; margin-left: -100px;">
                        <div class="elipse_hover_region">
                            <img id="hovered_region" alt="">
                        </div>
                        <div style="margin-left: 300px;">
                            <div style="display: flex; flex-direction: row; margin-top: 40px; margin-left: 40px; font-size: 20px; width: 300px;">
                                <p>What we have to offer on :</p>
                                <p style="text-align: left;" id="region_header"></p>
                            </div>
                            <p style="width: 400px; font-size: 16px;  margin-left: 20px;" id="hovered_text"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="box-sizing: border-box;width: 100%;height: 50%;bottom: 0;color: white; display: flex; flex-wrap: wrap; margin-left: 10%; margin-top: 100px;">
            <div class="elipse_planets_outer">
                <img class="region_floating_outer" src="images/planets/Arcadia.png" alt="" id ="Arcadia">
                <p style="margin-left: 60px; margin-top: 20px; width: 200px; font-size: 14px;">Arcadia - the cradle of human kind and last hope</p>
            </div>
            <div class="elipse_planets_inner">
                <p style="margin-top: -30px; margin-left: 80px; width: 200px; font-size: 14px; position: relative;" id="Earth">Earth - Humanities Home</p>
                <img class="region_floating_inner" src="images/planets/Earth.png" alt="" id ="Earth">
            </div>
            <div class="elipse_planets_inner">
                <p style="margin-top: -50px; margin-left: 80px; width: 200px; font-size: 14px; position: relative;" id="Venus">Venus - tourist destination & manmade hotsprings</p>
                <img class="region_floating_inner" src="images/planets/Venus.png" alt="" id ="Venus">
            </div>
            <div class="elipse_planets_outer">
                <img class="region_floating_outer" src="images/planets/Mars.png" alt="" id ="Mars">
                <p style="margin-left: 80px; width: 200px; font-size: 14px;">Mars - first colony and tourist attraction</p>
            </div>
        </div>
    </section>

    <section>
        <div class="left">
            <div class="content_header">
                <h1 style="text-align: left; height: 100px; margin-left: 60px; font-size: 40px;">Why interplanetary real estate?</h1>
            </div>
            <div class="content_text">
                <p style="width: 70%; margin-left: -60px; margin-top: -40px;"> Its no secret that earth is over populated, persuing interplanetary real estate can potentially motivate others to do the same and lift the burden of overpopulation.
                    We want to support interplanetary habitation to inspire innovation and push people to chase a dream they might have never thought of chasing. 
                    We want to set an example for others to follow in our footsteps.
                     </p>
            </div>
            <div class="main_content">
                <div class="opaque_panel2">
                    <div class="elipse2">
                        <img style="height: 330px; width: 330px; margin-top: 30px; border-radius: 50%;" src="images/backgrounds/pixel_landscape.png" alt="">
                    </div>
                </div>    
            </div>
        </div>
        <div class="right">
            <div class="main_content">
                    <div class="opaque_panel3">
                        <div class="elipse_2">
                            <img style="width:440px; height:440px; border-radius: 50%; margin-top: 30px;" src="images/properties/apartment.jpeg" alt="">
                        </div>
                        <div class="content_text">
                            <p style="margin-top: -100px;">This website was created to expand our knowledge of interplanetary realestate and what it would need to flourish to expand our knowledge to further understand the true purpose of real estate.</p>
                        </div>
                    </div>
            </div>
        </div>
    </section>
    <!-- End of About Us -->

    <!-- Contact us Sections Begin -->
    <section id="contact_us">
        <div class="left">
            <div class="content_header">
                <h1>Featured Agents</h1>
                <h1>of RealHome</h1>
            </div>
            <div class="main_content">
                <div class="opaque_panel4">
                    <div class="content_text">
                        <p>We are a team of agents that put your needs before all else, theres no dream too small or estate too big</p>
                        <a class="filter-link" href="/Pages/add_post.php">Contact us now</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="main_content">
                <div style="width: 100%; height: 800px; display: flex; flex-direction: column; gap: 20px; border-radius: 0 0 95px 95px; background: linear-gradient(0deg, rgba(217, 217, 217, 0.48) 0%,rgba(217, 217, 217, 0.24) 50%, rgba(115, 115, 115, 0.04) 100%); margin-left: -80px;">
                    <div style="width: 100%; height: 100%; display: flex; flex-wrap: row; gap: 20px; margin-left: 20px; padding-top: 50px;">
                        <div class="elipse3">
                            <img style="border-radius:50%; width:200px; height: 200px; margin-top: 10px;" id= "Agent1" alt="">
                            <p id= "Agent1name"></p>
                        </div>
                        <div class="elipse3">
                            <img style="border-radius:50%; width:200px; height: 200px; margin-top: 10px;" id= "Agent2"  alt="">
                            <p id= "Agent2name"></p>
                        </div>
                        <div class="elipse3">
                            <img style="border-radius:50%; width:200px; height: 200px; margin-top: 10px;" id= "Agent3"  alt="">
                            <p id= "Agent3name"></p>
                        </div>
                    </div>
                    <div class="agent_team_desc">
                        <p>At RealHome Real Estate, Wil, Casper, and Melissa worked together like a finely-tuned trio, each bringing their own strengths to the team. Wil, a sharp negotiator with years of experience, always knew how to secure the best deals for clients. He had a calm demeanor that reassured even the most anxious homebuyers. Casper, the tech-savvy agent, handled the listings and marketing, using innovative strategies to give their properties maximum exposure. His virtual tours and online campaigns consistently drew in new clients and inquiries.
        
                            Melissa, known for her warm personality and attention to detail, handled the client relationships. She made sure that every family or individual she worked with felt heard and understood, guiding them through the buying process with patience and care. Together, the three agents worked in sync, bouncing ideas off each other and sharing insights to ensure every client had the best experience possible.
                            
                            Whether they were discussing potential leads over coffee or visiting properties with prospective buyers, the team thrived on their combined strengths, always driven by one goal: helping people find the perfect place to call home.</p>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
    <section>
        <div style="color: white;width: 100%;height: fit-content;padding:20px;text-align: center;align-items: center;justify-content: center;display: flex;flex-wrap: wrap; background: linear-gradient(90deg, #ffffff56 0%,  #ffffff10 25%, #ffffff00 50%, #ffffff10 75%, #ffffff56 100%);">
            <div class="sub_content_3rd">
                <div class="elipse_Large_contact">
                    <div style="width: 350px; height:350px; border-radius: 50%; border-width: 5px; border-color: #7c00b1; border-style: solid;">
                        <img src="images/icons/user.png" alt="">
                        <div class="content_header">
                            <h6>Speak to an agent</h6>
                        </div>
                        <div class="content_text">
                            <p>We will help you find your dream home contact us now</p>
                        </div>
                        <a href="/Pages/add_post.php?user=<?php echo $_SESSION['user']?>&type=property" class="filter-link">Contact us</a>
                    </div>
                </div>
            </div>
            <div class="sub_content_3rd">
                <div class="elipse_Large_contact">
                    <div style="width: 350px; height:350px; border-radius: 50%; border-width: 5px; border-color: #7c00b1; border-style: solid;">
                    <img src="images/icons/user.png" alt="">
                        <div class="content_header">
                            <h6>Support</h6>
                        </div>
                        <div class="content_text">
                            <p>Have a concern? Query us today and we will contact you swiftly.</p>
                        </div>
                        <a href="/Pages/add_post.php?user=<?php echo $_SESSION['user']?>&type=concern" class="filter-link">Contact us</a>
                    </div>
                </div>
            </div>
            <div class="sub_content_3rd">
                <div class="elipse_Large_contact">
                    <div style="width: 350px; height:350px; border-radius: 50%; border-width: 5px; border-color: #7c00b1; border-style: solid;">
                    <img src="images/icons/user.png" alt="">
                        <div class="content_header">
                            <h6>Report a bug</h6>
                        </div>
                        <div class="content_text">
                            <p>Experiencing a bug on the website lets squash it together!</p>
                        </div>
                            <a href="/Pages/add_post.php?user=<?php echo $_SESSION['user']?>&type=bug" class="filter-link">Contact us</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
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
   <script src="js/Script.js"></script>
    <script>
    
    </script>
</body>
</html>