<?php
session_start();
error_log("Session in about_us.php: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Code Innovators</title>
    <link rel="stylesheet" href="..\css\styles.css">
    <link rel="stylesheet" href="..\css\header-footer.css">
    <link rel="stylesheet" href="..\css\about-us.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Josefin+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <section class="hero">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="..\index.php"><img src="..\Assets\Images\Logo.png" alt="logo"></a>
                </div>
                <div class="navlinks">
                    <li><a href="..\index.php">Home</a></li>
                    <li><a href="timetable.php">Timetable</a></li>
                    <li><a href="discussion.php">Discussion</a></li>
                    <li><a href="share_resources.php">Share Resources</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
                    <button id="login-button" class="button"></button>
                </div>
            </nav>
            <div class="content-container">
                <div class="about-description">
                    <h2>Meet Our Team:</h2>
                    <p>
                        At Code Innovators, we are a team of passionate coders dedicated to crafting exceptional websites that 
                        bring your digital vision to life. With a blend of creativity, technical expertise, and a deep understanding 
                        of the latest web technologies, we design and develop user-friendly, responsive, and visually stunning websites. 
                        Whether you’re a startup looking to establish your online presence or a business seeking to enhance your digital 
                        footprint, we’re here to code your success story
                    </p>
                </div>
                <div class="about-images">
                    <div class="profile-card prof-top">
                        <div class="profile-image">
                            <img src="..\Assets\Images\Janco.jpg" alt="prof-img">
                            <div class="image-overlay">
                                <div class="profile-info">
                                    <p class="name">Janco Van Heerden</p>
                                    <p>UI/UX Designer, Back-End Developer & System Architect</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card">
                        <div class="profile-image">
                            <img src="..\Assets\Images\Amy.jpg" alt="prof-img">
                            <div class="image-overlay">
                                <div class="profile-info">
                                    <p class="name">Amy Lee Baker</p>
                                    <p>Front-End Developer, Code Debugger & Project Supervisor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card">
                        <div class="profile-image">
                            <img src="..\Assets\Images\CasperW.jpg" alt="prof-img">
                            <div class="image-overlay">
                                <div class="profile-info">
                                    <p class="name">Casper Andries Willemse</p>
                                    <p>Front-End Developer & Project Manager</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card prof-top">
                        <div class="profile-image">
                            <img src="..\Assets\Images\CassieH.jpg" alt="prof-img">
                            <div class="image-overlay">
                                <div class="profile-info">
                                    <p class="name">Casper Hendriks</p>
                                    <p>UI/UX Designer, Back-End Developer & Code Tester</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <section class="linkedin">
        <div class="linked-button">
            <a href="https://www.linkedin.com/in/janco-van-heerden/"><button id="linked" class="button b-top">LinkedIn</button></a>
        </div>
        <div class="linked-button">
            <a href="https://www.linkedin.com/in/amy-lee-baker-398a77252/"><button id="linked" class="button b-bot">LinkedIn</button></a>
        </div>
        <div class="linked-button">
            <a href="https://www.linkedin.com/in/casper-willemse-489892262/?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=android_app"><button id="linked" class="button b-bot">LinkedIn</button></a>
        </div>
        <div class="linked-button">
            <a href="https://www.linkedin.com/in/cassie-hendriks-439419241/"><button id="linked" class="button b-top">LinkedIn</button></a>
        </div>
    </section>

    <section class="information">
        <div class="info-container i1">
            <h1>WHAT WE DO:</h1>
            <h2>Developing Educational Solutions for the Future</h2>
            <p>
                Our team at Code Innovators is dedicated to crafting innovative digital solutions designed to enhance the educational experience.
                We specialize in creating comprehensive and interactive websites that serve as powerful tools for students and educators alike.
                With the development of the CTU-Buddy educational system, we are focused on building a platform that fosters learning, collaboration, and academic success.
            </p>
        </div>
        <div class="info-container i2">
            <h1>WHY WE DO IT:</h1>
            <h2>Empowering Education through Technology</h2>
            <p>
                Education is the cornerstone of a thriving society, and we believe that technology can transform the way students learn and interact.
                Our goal is to create digital platforms that make education more accessible, engaging, and effective.
                By developing CTU-Buddy, we aim to revolutionize the educational landscape at City Technical University,
                providing students with the tools they need to succeed in their academic journey.
            </p>
        </div>
        <div class="info-container i3">
            <h1>HOW WE WORK:</h1>
            <h2>Collaboration, Innovation, Precision</h2>
            <p>
                Our approach to development is deeply collaborative. We work closely as a team to brainstorm, design, and implement solutions that meet the unique needs of our users.
                For CTU-Buddy, we have meticulously planned every detail—from the intuitive design of the Timetable page to the interactive features of the Discussion platform.
                Each component is crafted with the user in mind, ensuring a seamless and enriching experience.
            </p>
        </div>
        <div class="info-container i4">
            <h1>OUR VISION:</h1>
            <h2>Transforming the Learning Experience</h2>
            <p>
                We envision a future where technology seamlessly integrates with education to create more dynamic and interactive learning environments.
                CTU-Buddy is just the beginning. Our vision is to continue pushing the boundaries of educational technology, creating tools that inspire and empower the next generation of learners.
            </p>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-navlinks">
                <div class="left-links">
                    <li><a href="..\index.php">Home</a></li>
                    <li><a href="timetable.php">Timetable</a></li>
                    <li><a href="discussion.php">Discussion</a></li>
                </div>
                <div class="right-links">
                    <li><a href="share_resources.php">Share Resources</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="contact_us.php">Contact Us</a></li>
                </div>
            </div>
            <div class="footer-logo">
                <a href="..\index.php"><img src="..\Assets\Images\Logo.png" alt="logo"></a>
            </div>
            <div class="footer-right">
                <div class="socials">
                    <a href="https://x.com/Code_Innovators"><i class="fa fa-twitter"></i></a>
                    <a href="https://www.youtube.com/@Code_Innovators"><i class="fa fa-youtube"></i></a>
                    <a href="https://github.com/Code-Innovators-Design"><i class="fa fa-github"></i></a>
                    <a href="https://www.instagram.com/code_innovators_/"><i class="fa fa-instagram"></i></a>
                </div>
                <div class="copy">
                    <h2>© 2024 Code Innovators, Inc.</h2>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const loginButton = document.getElementById("login-button");
            console.log("PHP Session:", <?php echo json_encode($_SESSION); ?>);
            console.log("Username:", <?php echo json_encode($_SESSION['username'] ?? null); ?>);
            
            <?php if (isset($_SESSION['username'])): ?>
                console.log("User is logged in as: " + <?php echo json_encode($_SESSION['username']); ?>);
                loginButton.textContent = "Log Out";
                loginButton.onclick = function() {
                    window.location.href = "../pages/logout.php";
                };
            <?php else: ?>
                console.log("User is not logged in");
                loginButton.textContent = "Log In";
                loginButton.onclick = function() {
                    window.location.href = "../pages/login.php";
                };
            <?php endif; ?>
        });
    </script>
</body>
</html>