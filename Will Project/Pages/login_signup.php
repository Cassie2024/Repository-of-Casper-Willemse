<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/main_styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap');
        input{
            width:100%;
            height:25px;
            border-radius:10px;
            opacity:0.5;
        }
        select{
            width:100%;
            height:25px;
            border-radius:10px;
            opacity:0.5;
        }
        #signup-description{    
            width:100%;
            border-radius:10px;
            opacity:0.5;
        }
        #imageContainer{
            border-style:none;
        }
        #imageContainer::-webkit-scrollbar {
            display: none; /* Chrome, Safari, and Opera */
        }
        .form-container{
            border-radius:95px;
            padding:30px;
            width:50%;
            height:75%;
            display:flex;
            flex-direction:column;
            align-items:center; 
            justify-items:center; 
            background: linear-gradient(180deg, rgba(217, 217, 217, 0.24) 0%, rgba(115, 115, 115, 0.00) 100%);
        }
        .login_form-container{
            border-radius:95px;
            padding:30px;
            width:50%;
            height:45%;
            display:flex;
            flex-direction:column;
            align-items:center; 
            justify-content:center;

            background: linear-gradient(180deg, rgba(217, 217, 217, 0.24) 0%, rgba(115, 115, 115, 0.00) 100%);
        }

        .login-link {
            position: relative;
            width: fit-content; 
            height: 25px;
            padding-left: 10px;
            padding-right: 10px;
            margin-top: 50px;
            text-align: center;
            border-radius: 20px;
            margin-left:10px;
            margin-right:10px; 
            background-color:#461964;
            color: azure;
            border-style: none;
            text-decoration: none;
        }
        .login-link:hover{
            background-color: mediumslateblue;
        }
    </style>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="">

    <!-- Sign Up Section -->
    <section id="signup" style="display:flex;flex-direction:column; width:100%;">
        <h2>Sign Up</h2>
        <form action="/Pages/register.php" method="POST" class="form-container">
            <label for="signup-username">Username:</label>
            <input type="text" id="signup-username" name="username" required>

            <label for="signup-email">Email:</label>
            <input type="email" id="signup-email" name="email" required>

            <label for="signup-password">Password:</label>
            <input type="password" id="signup-password" name="password" required>
            
            <label for="signup-accounttype">Account Type:</label>
            <select id="signup-accounttype" name="type" required>
                <option value="" disabled selected>Select an option</option>
                <option value="User">User</option>
                <option value="Agent">Agent</option>
            </select>

            <!-- Label for Icons -->
            <label for="signup-icons">Select an Icon:</label>

            <?php
            // Directory containing the images
            $dir = '../images/users';

            // Check if directory exists
            $images = [];
            if (is_dir($dir)) {
                if ($handle = opendir($dir)) {
                    // Loop through directory files
                    while (false !== ($file = readdir($handle))) {
                        if ($file != '.' && $file != '..') {
                            $images[] = htmlspecialchars($file); // Store valid image files
                        }
                    }
                    closedir($handle);
                }
            }
            ?>

            <!-- Main container for icons -->
            <div id="imageContainer" style="height: 200px; width: 100%; overflow-y: scroll; border: 1px solid #ccc; display: grid; grid-template-columns: repeat(auto-fill, 40px); grid-gap: 5px;">
                <?php foreach ($images as $file): ?>
                    <!-- Each image in the directory inside a 40px by 40px div -->
                    <div class="image-div" style="width: 40px; height: 40px; cursor: pointer;">
                        <img src="<?php echo $dir . '/' . $file; ?>" data-file="<?php echo $file; ?>"
                             style="width: 100%; height: 100%;" class="thumbnail">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Hidden input to store the selected image file value -->
            <input type="hidden" id="selectedImage" name="img" required>

            <!-- Optional Preview of the Selected Image -->
            <div id="imagePreview" style="margin-top: 10px; height:80px;">
                <label for="previewImage">selected Image:</label>
                <img id="previewImage" src="" style="width: 40px;">
            </div>

            <!-- Description Section -->
            <div style="display:flex;flex-direction:column; width:100%;">
                <label for="description">Description:</label>
                <textarea id="signup-description" name="description" required></textarea>
            </div>

            <label for="signup-agency">agency:</label>
            <input type="text" id="signup-agency" name="agency" required>
            <div style="display:flex;flex-direction:row;">
                <button type="submit" class="login-link">Sign Up</button>
                <a class="filter-link" style="font-size:15px;" href="#login">Login section</a>
            </div>    
        </form>
    </section>

    <!-- Login Section -->
    <section id="login" style="display:flex;flex-direction:column; width:100%;">
        <h2>Login</h2>
        <form action="/Pages/login.php" method="POST" class="login_form-container">
            <label for="login-username">Username:</label>
            <input type="text" id="login-username" name="username" required>

            <label for="login-password">Password:</label>
            <input type="password" id="login-password" name="password" required>

            <div style="display:flex;flex-direction:row;">
                <button type="submit" class="login-link">Log in</button>
                <a class="filter-link" style="font-size:15px;" href="#signup">Sign up section</a>
            </div>
        </form>
    </section>

    <script>
        // JavaScript to handle icon selection and updating hidden input
        document.querySelectorAll('.thumbnail').forEach(img => {
            img.addEventListener('click', function() {
                const selectedImageSrc = this.src;
                const selectedImageFile = this.getAttribute('data-file');
                const previewImage = document.getElementById('previewImage');
                const selectedImageInput = document.getElementById('selectedImage');

                // Update the hidden input value with the selected image file name
                selectedImageInput.value = selectedImageFile;

                // Show the selected image in the preview
                previewImage.src = selectedImageSrc;
                previewImage.style.display = 'block'; // Show the preview
            });
        });
    </script>
</body>
</html>
