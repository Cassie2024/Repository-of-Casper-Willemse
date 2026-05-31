<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Resources</title>
    <link rel="stylesheet" href="..\css\timetable.css">
    <link rel="stylesheet" href="..\css\header-footer.css">
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
        </div>
    </section>
    <div class="content">
      <div class="description">
        <h1>Unlock <span class="your-potential">Your Potential</span> with <br>CTU's University Timetable</h1>
        <p>At CTU, our university timetable is meticulously crafted to optimize your learning experience,
          giving you the structure and support you need to thrive academically. Every moment of your time at CTU is valuable,
          and we've designed each session to equip you with the knowledge and skills required to become a genius in your chosen field. By following our carefully planned schedule,
          you are investing in your future success. Join us at CTU, where we turn passion into expertise and dreams into reality.
        </p>
      </div>
    </div>
    <div class="timetable">
      <img src="..\Assets\Images\circle_about.png" alt="Circular Image" class="circle-img">
      <table>
          <tr>
              <th>CWD 6pm-9pm</th>
              <th>CF/RD 6pm-9pm</th>
              <th>ENA, 9am-12am</th>
          </tr>
          <tr>
              <td>23/07/24</td>
              <td>25/07/24</td>
              <td></td>
          </tr>
          <tr>
              <td>30/07/24</td>
              <td>08/08/24</td>
              <td>08/03/24</td>
          </tr>
           <tr>
              <td>06/08/24</td>
              <td>08/08/24</td>
              <td></td>
          </tr>
           <tr>
              <td>13/08/24</td>
              <td>15/08/24</td>
              <td>17/08/24</td>
          </tr>
           <tr>
              <td>20/08/24</td>
              <td>22/08/24</td>
              <td></td>
          </tr>
           <tr>
              <td>27/08/24</td>
              <td>29/08/24</td>
              <td>31/08/24</td>
          </tr>
           <tr>
              <td>03/09/24</td>
              <td>05/09/24</td>
              <td></td>
          </tr>
           <tr>
              <td>10/09/24</td>
              <td>12/09/24</td>
              <td>14/09/24</td>
          </tr>
           <tr>
              <td>17/09/24</td>
              <td>19/09/24</td>
              <td></td>
          </tr>
           <tr>
              <td>01/10/24</td>
              <td>03/10/24</td>
              <td>05/10/24</td>
          </tr>
           <tr>
              <td>08/10/24</td>
              <td>10/10/24</td>
              <td></td>
          </tr>
           <tr>
              <td>15/10/24</td>
              <td>17/10/24</td>
              <td>19/10/24</td>
          </tr>
      </table>
      <div class="vertical-line"></div>
      <a href="../Assets/Images/Timetable.pdf" download="Timetable.pdf">
        <button class="button2">Download Timetable</button>
        </a>
      <img src="..\Assets\Images/ctu_border.png" alt="CTU Image" class="ctu-border-img">
        <div class="knowledge1">
          <h1>Empower <span class="knowledge">Your Knowledge</span> with <br>Us.</h1>
        </div>
    </div>
  </div>
  <div class = "programming_foundation">
    <img src="..\Assets\Images/pic1.jpeg" alt="profile Image" class="profile-img">
    <div class = "pf">
      <h1>Programming Foundation</h1>
      <p>Build Your Coding Expertise With Us</p>
    </div>
    <div class = "the3s1">
      <h1>3 Days</h1>
      <p>This dedicated time each week ensures focused and consistent learning.
        It allows for steady progress and mastery of key concepts without overwhelming your schedule.</p>
      </div>
      <div class = "the3s2">
        <h1>3 Hours</h1>
        <p>This daily commitment fosters deep, immersive learning and accelerates mastery of complex topics.
          It balances focused study with regular practice to achieve significant academic progress.</p>
        </div>
        <div class = "line3">
          <hr>
        </div>
        <div class="circlewords">
          <img src="..\Assets\Images/white_circle_empty.png" alt="words" class="empty-circle-img">
        <p><span class="t-bold">Three Hours, Three Days:</span> This Perfect Formula Was Designed to Master Core Concepts Efficiently</p>
        </div>
    <div class = "important_dates">
      <h1>IMPORTANT DATES</h1>
      <div class="purpbox-container"></div>
      <div class="purpbox-container">
        <!-- First Column of purpboxes -->
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>Fun Flower Day</p>
            </div>
            <div class="ellipse-1">
              <p>06/09</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>International Exam End</p>
            </div>
            <div class="ellipse-1">
              <p>08/11</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>Student Holiday - START</p>
            </div>
            <div class="ellipse-1">
              <p>23/09</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>National Exam Prep Week Start</p>
            </div>
            <div class="ellipse-1">
              <p>11/11</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>Student Holiday - END</p>
            </div>
            <div class="ellipse-1">
              <p>28/09</p>
            </div>
        </div>
    
        <!-- Second Column of purpboxes -->
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>National Exam Prep Week End</p>
            </div>
            <div class="ellipse-1">
              <p>16/11</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>Prelim Week Start</p>
            </div>
            <div class="ellipse-1">
              <p>21/10</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>National Exam Start</p>
            </div>
            <div class="ellipse-1">
              <p>19/11</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>International Exam Start</p>
            </div>
            <div class="ellipse-1">
              <p>04/11</p>
            </div>
        </div>
        <div class="purpbox frame-1">
            <div class="rectangle-1">
              <p>Student Holiday start</p>
            </div>
            <div class="ellipse-1">
              <p>03/12</p>
            </div>
        </div>
    </div>
    <div class="ellipse-2"></div>
    <div class="ellipse-3"></div>
    <div class="ellipse-4"></div>
    <div class="ellipse-5"></div>
    <div class="ellipse-6"></div>
    <div class="ellipse-7"></div>
    <div class="hr1"><hr/></div>
    <div class="hr2"><hr/></div>
    <div class="hr3"><hr/></div>
    <div class="hr4"><hr/></div>
    <div class="hr5"><hr/></div>
    <div class="hr6"><hr/></div>
    <div class="vertical-line1"></div>
    <div class="vertical-line2"></div>
    <div class="vertical-line3"></div>
    <div class="vertical-line4"></div>
    <div class="vertical-line5"></div>
    <div class="vertical-line6"></div>
    <div class="vertical-line7"></div>
    <div class="vertical-line8"></div>
    <div class="vertical-line9"></div>
    <div class="vertical-line10"></div>
    <div class="horizontal-lines-container">
      <div class="horizontal-line1"></div>
      <div class="horizontal-line1"></div>
      <div class="horizontal-line1"></div>
      <div class="horizontal-line1"></div>
      <div class="horizontal-line1"></div>
  </div>
  <div class="horizontal-lines-container2">
    <div class="horizontal-line2"></div>
    <div class="horizontal-line2"></div>
    <div class="horizontal-line2"></div>
    <div class="horizontal-line2"></div>
  </div>

<div class ="texts">
  <div class="subject">
    <div class="cwd">
      <p>CWD</p>
    </div>
    <div class="cwd-html">
      <p>HTML</p>
    </div>
    <div class="cwd-css">
      <p>CSS</p>
    </div>
    <div class="cf">
      <p>CF</p>
    </div>
    <div class="ena">
      <p>ENA</p>
    </div>
    <div class="rd">
      <p>RD</p>
    </div>
  </div>
</div>

      </div>
    </div>
  </div>

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