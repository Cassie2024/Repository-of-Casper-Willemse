<?php
session_start();
require('config.php'); // Include your database credentials

// Database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch recent posts (if needed)
$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTU-Buddy Landing Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/header-footer.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Josefin+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .button {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            color: white;
            background: linear-gradient(to right, #7500c3, #8e30c4);
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(to left, #7500c3, #8e30c4);
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .navlinks {
                display: none;
                flex-direction: column;
                width: 100%;
                text-align: center;
            }

            .navlinks.active {
                display: flex;
            }

            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .menu-toggle {
                display: block;
                cursor: pointer;
                font-size: 24px;
                margin: 10px;
            }

            .content {
                flex-direction: column;
            }

            .description, .image-container {
                width: 100%;
            }

            .feature-container {
                flex-direction: column;
            }

            .dream-container, .academic-container {
                flex-direction: column;
            }

            .dream-img, .academic-img {
                width: 100%;
            }

            video {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="circle-background"></div>
        <div class="container">
            <nav class="navbar">
                <div class="logo-container">
                    <a href="index.php">
                        <img src="Assets/Images/Logo.png" alt="CTU-Buddy logo" class="logo-img">
                    </a>
                </div>
                <div class="menu-toggle" onclick="toggleMenu()">☰</div>
                <div class="navlinks">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="pages/timetable.php">Timetable</a></li>
                    <li><a href="pages/discussion.php">Discussion</a></li>
                    <li><a href="pages/share_resources.php">Share Resources</a></li>
                    <li><a href="pages/about_us.php">About Us</a></li>
                    <li><a href="pages/contact_us.php">Contact Us</a></li>
                    <button id="login-button" class="button"></button>
                </div>
            </nav>
            <div class="content">
                <div class="description">
                    <h1>Welcome to <span class="ctu-buddy">CTU-Buddy</span></h1>
                    <p>
                        CTU-Buddy is designed to be your go-to resource for everything
                        related to your university experience. Whether you need help with course schedules,
                        finding the best study spots, or staying on top of important deadlines, CTU-Buddy has you covered. Our
                        platform is built to ensure you have all the tools you need to
                        succeed and make the most of your time at university. Join us and
                        discover how CTU-Buddy can make your academic journey
                        smoother, more organized, and a lot more fun!
                    </p>
                    <div class="about-us-container">
                        <a href="pages/about_us.html">
                        <button id="about-us-button" class="button">More about what we do</button>
                        </a>
                    </div>
                </div>
                <div class="image-container">
                    <img src="Assets/Images/Laptop.png" alt="laptop" id="laptop">
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="feature-container">
            <div class="goals-img img-div">
                <img src="Assets/Images/pic1.jpeg" alt="goals-img">
                <figcaption id="goals-caption">Achieve Your Goals</figcaption>
            </div>
            <div class="connected-img img-div mid">
                <img src="Assets/Images/pic2.jpeg" alt="connected-img">
                <figcaption id="connected-caption">Stay Connected</figcaption>
            </div>
            <div class="bank-img img-div">
                <img src="Assets/Images/pic3.jpg" alt="bank-img">
                <figcaption id="bank-caption">Study Without Breaking the Bank</figcaption>
            </div>
        </div>
    </section>

    <!-- First Video Section -->
    <section class="vid-section">
        <div class="dream-container">
            <div class="dream-img">
                <video id="dream-video" width="550" height="725" controls>
                    <source src="Assets/Videos/testemonial.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="dream-content">
                <h2 class="dream-title">Reach <span class="ctu-buddy">Your Dream</span> one day at a time</h2>
                <p>
                    If you don't believe us, believe them—our students are already on their way to success.
                    Hear their stories, see their achievements, and discover how CTU-Buddy has helped them take one step closer to their
                    dreams every day.
                </p>
                <div class="button-container">
                    <button id="play-now-button" class="button" onclick="playVideo()">Play Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Second Video Section -->
    <section class="vid-section">
        <div class="academic-container">
            <div class="academic-content">
                <h2 class="academic-title">Advance <span class="ctu-buddy">Your Academic</span> Journey</h2>
                <p>
                    Unlock new opportunities with our innovative programs and supportive community.
                    Explore how our resources and guidance are helping students excel in their academic pursuits.
                </p>
                <div class="button-container">
                    <button id="play-academic-button" class="button" onclick="playAcademicVideo()">Play Now</button>
                </div>
            </div>
            <div class="academic-img">
                <video id="academic-video" width="550" height="725" controls>
                    <source src="Assets/Videos/academic.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </section>

    <!-- JavaScript for controlling video playback -->
    <script>
        function playVideo() {
            var video = document.getElementById("dream-video");
            video.play();
        }
        function playAcademicVideo() {
            var video = document.getElementById("academic-video");
            video.play();
        }

        // Login/Logout functionality
        document.addEventListener("DOMContentLoaded", function () {
            const loginButton = document.getElementById("login-button");
            const isLoggedIn = localStorage.getItem("loggedIn");

            if (isLoggedIn) {
                loginButton.textContent = "Log Out";
                loginButton.onclick = function() {
                    localStorage.removeItem("loggedIn");
                    alert("You have been logged out.");
                    location.reload();  // Reload the page to update the button
                };
            } else {
                loginButton.textContent = "Log In";
                loginButton.onclick = function() {
                    localStorage.setItem("loggedIn", "true");
                    window.location.href = "pages/login.php";  // Redirect to login page
                };
            }
        });

        function toggleMenu() {
            const navlinks = document.querySelector('.navlinks');
            navlinks.classList.toggle('active');
        }
    </script>

    <footer>
        <div class="footer-container">
            <div class="footer-navlinks">
                <div class="left-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="pages/timetable.php">Timetable</a></li>
                    <li><a href="pages/discussion.php">Discussion</a></li>
                </div>
                <div class="right-links">
                    <li><a href="pages/share_resources.php">Share Resources</a></li>
                    <li><a href="pages/about_us.php">About Us</a></li>
                    <li><a href="pages/contact_us.php">Contact Us</a></li>
                </div>
            </div>
            <div class="footer-logo">
                <a href="index.php"><img src="Assets/Images/Logo.png" alt="logo"></a>
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
        function toggleComments(postId) {
            const commentSection = document.getElementById('comments-' + postId);
            commentSection.style.display = commentSection.style.display === 'none' || commentSection.style.display === '' ? 'block' : 'none';
        }

        // Login/Logout functionality
        document.addEventListener("DOMContentLoaded", function () {
            const loginButton = document.getElementById("login-button");
            <?php if (isset($_SESSION['username'])): ?>
                loginButton.textContent = "Log Out";
                loginButton.onclick = function() {
                    window.location.href = "../pages/logout.php";  // Redirect to logout script
                };
            <?php else: ?>
                loginButton.textContent = "Log In";
                loginButton.onclick = function() {
                    window.location.href = "../pages/login.php";  // Redirect to login page
                };
            <?php endif; ?>
        });
    </script>
</body>
        
</html>

<?php
$conn->close(); // Close the connection
?>
