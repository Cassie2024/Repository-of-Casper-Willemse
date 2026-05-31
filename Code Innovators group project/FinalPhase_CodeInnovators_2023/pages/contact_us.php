<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Code Innovators</title>
    <script>
        function createdesktop() {
    const body = document.getElementById('monitor-body');
    body.innerHTML = ''; // Clear monitor body

    const grid = document.createElement("div");
    grid.className = 'grid';

    const Items = 29;
    for (let i = 1; i < Items; i++) {
        const gridItem = document.createElement('div');
        gridItem.className = 'grid-Item'; // Match with the CSS class

        switch (i) {
            case 1:
                createButton(gridItem, '../Assets/Images/mail.png', 'Mail', 'Mail');
                break;
            case 8:
                createButton(gridItem, '../Assets/Images/map.png', 'Location', 'Location');
                break;
            case 15:
                createButton(gridItem, '../Assets/Images/contacts.png', 'Contacts', 'Contacts');
                break;
            case 28:
                createButton(gridItem, '../Assets/Images/recycling.png', 'Recycle bin');
                break;
        }

        grid.appendChild(gridItem);
    }

    body.appendChild(grid); // Append the grid to the monitor body
}

function createButton(gridItem, imageSrc, labelText, url = null) {
    const buttonContainer = document.createElement('div');
    buttonContainer.className = 'button-container';
    buttonContainer.style.display = 'flex';
    buttonContainer.style.flexDirection = 'column';
    buttonContainer.style.alignItems = 'center';
    buttonContainer.style.padding = '-20px'; // Adjust padding as needed
    buttonContainer.style.color = "white";

    const button = document.createElement('button');
    button.className = 'image-button';
    button.style.backgroundColor = 'black';
    button.style.border = 'none';
    button.style.padding = '0'; // Adjust padding inside the button if needed

    if (url === 'Mail') {
        button.onclick = () => {
            displayForm();
        };
    } else if (url === 'Location') {
        button.onclick = () => {
            displayLocation(); // Redirect to the specified URL
        };
    } else if (url === 'Contacts') {
        button.onclick = () => {
            displaycontacts(); // Redirect to the specified URL
        };
    }

    const image = document.createElement('img');
    image.src = imageSrc;
    image.alt = labelText;
    image.style.width = '10px'; // Adjust width as needed
    image.style.height = '10px'; // Adjust height as needed

    button.appendChild(image);

    const label = document.createElement('div');
    label.className = 'label';
    label.textContent = labelText;
    label.style.fontSize = '4px'; // Adjust font size as needed
    label.style.marginTop = '-3px'; // Adjust margin above the label

    buttonContainer.appendChild(button);
    buttonContainer.appendChild(label);

    gridItem.appendChild(buttonContainer);
}

function displayForm() {
    const monitorBody = document.getElementById('monitor-body');
    monitorBody.innerHTML = ''; // Clear monitor body

    monitorBody.innerHTML = `
      <form class="form-container" id="form-monitor">
        <div class="form-group">
            <label for="email">Company Email</label>
            <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}$" required>
        </div>
        <div class="form-group">
            <label for="textarea">How Can We Help You?</label>
            <textarea required cols="50" rows="10" id="textarea" name="textarea"></textarea>
        </div>
        <div class="form-buttons" style="display: flex; justify-content: space-between; margin-top: 10px;">
            <button type="button" id="back" class="btn-back">Back</button>
            <button type="submit" id="submit" class="form-submit-btn">Submit</button>
        </div>
      </form>`;

    document.getElementById("back").addEventListener("click", () => {
        createdesktop(); // Recreate the desktop view
    });

    document.getElementById("form-monitor").addEventListener("submit", (e) => {
        e.preventDefault();
        // Handle form submission here
        alert("Form submitted successfully!");
        createdesktop(); // Return to desktop view after submission
    });

    // Add hover effect using JavaScript
    const submitButton = document.querySelector('.form-submit-btn');
    submitButton.addEventListener('mouseover', () => {
        submitButton.style.backgroundColor = '#0056b3';
    });
    submitButton.addEventListener('mouseout', () => {
        submitButton.style.backgroundColor = '#007bff';
    });
}

function displaycontacts() {
    const monitorBody = document.getElementById('monitor-body');
    monitorBody.innerHTML = ''; // Clear monitor body

    monitorBody.innerHTML = `
    <div class="contacts-container">
      <div class="contacts-div">
        <p>Contacts</p>
        <button class="btn-contactlink">Janco Van Heerden- 20240185</button>
        <button class="btn-contactlink">Casper Hendriks - 20241167</button>
        <button class="btn-contactlink">Casper Andries Willemse - 20241237</button>
        <button class="btn-contactlink">Amy Baker - 20240116</button>
      </div>
      <button id="back" class="btn-back">Back</button>
    </div>`;

    document.getElementById("back").addEventListener("click", () => {
        createdesktop(); // Recreate the desktop view
    });
}

function displayLocation() {
    const monitorBody = document.getElementById('monitor-body');
    monitorBody.innerHTML = ''; // Clear monitor body

    monitorBody.innerHTML = `
    <form class="Location-container">
      <div class="address-div">
        <p>South Africa, Freestate</p>
        <p>Bloemfontein, Langenhoven Park, 9301</p>
        <p>1st Floor, Pretty Suites Office Block</p>
        <button id="back" class="btn-back">Back</button>
      </div>
      <div class="address-div">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3486.761293538482!2d26.154309312000038!3d-29.083197987269457!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1e8fdb6a5867b92b%3A0x17e2c5b46d445057!2sPretty%20Suites!5e0!3m2!1sen!2sza!4v1665680929341!5m2!1sen!2sza" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </form>`;

    document.getElementById("back").addEventListener("click", () => {
        createdesktop(); // Recreate the desktop view
    });
}

createdesktop();

    </script>
        <style>
			.btn-back {
        
                    }
            .form-submit-btn {
                float: right;
                margin-right: 10px;
                background-color: #007bff; /* Use your preferred color */
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }
            .form-submit-btn:hover {
                background-color: #0056b3; /* Darker shade for hover effect */
            }
            .form-buttons {
                display: flex;
                justify-content: space-between;
                margin-top: 10px;
            }
        </style>
    <link rel="stylesheet" href="..\css\styles.css">
    <link rel="stylesheet" href="..\css\header-footer.css">
    <link rel="stylesheet" href="../css/contact_ons.css">
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
                    <button id="login-button" class="button">Log In</button>
                </div>
            </nav>
        </div>
    </section>


    <section class="laptop-body">
        <div class="laptop">
            <div class="monitor">
                <div class="monitor-body" id="monitor-body"></div>
                <div id="app"></div>
            </div>
                <div class="contactlft">
                <div class="description3">
                    <h1>Get in <span class="in-contact">In Contact</span> with us!</h1>
                    <p>
                        CTU-Buddy is designed to be your go-to resource for everything
                        related to your university experience. Whether you need help with course schedules,
                        finding the best study spots, or staying on top of important deadlines, CTU-Buddy has you covered. Our
                        platform is built to ensure you have all the tools you need to
                        succeed and make the most of your time at university. Join us and
                        discover how CTU-Buddy can make your academic journey
                        smoother, more organized, and a lot more fun!
                    </p>
                </div>
    </div>
            <div class="bottom_part">
                <div class="keyboard">
                    <img src="https://i.ibb.co/sVWfnWX/keyboard-black.png"/>
                </div>
                <div style="display: flex; justify-content: center; align-items: center;">
                    <div class="touchpad"></div>
                </div>
            </div>
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

    <script src="../scripts/laptop.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const loginButton = document.getElementById("login-button");
        <?php if (isset($_SESSION['username'])): ?>
            loginButton.textContent = "Log Out";
            loginButton.onclick = function() {
                window.location.href = "../pages/logout.php";
            };
        <?php else: ?>
            loginButton.textContent = "Log In";
            loginButton.onclick = function() {
                window.location.href = "../pages/login.php";
            };
        <?php endif; ?>
    });
    </script>
</body>
</html>
