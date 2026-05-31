<?php
session_start();
require('../config.php'); // Include config file for database connection

$username = $_SESSION['username'] ?? 'Guest'; // Get the username from the session

// Database connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle post submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_text'])) {
    $post_text = $_POST['post_text'];
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_path = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_path = '../uploads/' . basename($image_name); // Ensure the path is correct

        // Move the uploaded file to the uploads directory
        move_uploaded_file($image_tmp_path, $image_path);
    }

    // Insert the post into the database
    $stmt = $conn->prepare("INSERT INTO posts (username, post_text, image_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $post_text, $image_path);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_text'])) {
    $post_id = $_POST['post_id'];
    $comment_text = $_POST['comment_text'];

    // Insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (post_id, username, comment_text) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $post_id, $username, $comment_text);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle post deletion
if (isset($_GET['delete_post'])) {
    $post_id = $_GET['delete_post'];
    $conn->query("DELETE FROM posts WHERE id = $post_id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle comment deletion
if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];
    $conn->query("DELETE FROM comments WHERE id = $comment_id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch posts
$posts = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussions</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header-footer.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Josefin+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .user_container {
            display: flex;
            align-items: center;
        }
        .post_input {
            width: 100%;
            max-width: 600px;
            height: 80px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            resize: vertical;
        }

        .user_container img {
            margin-right: 10px;
        }
        .post_container {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            position: relative;
        }
        .post_date {
            position: absolute;
            top: 10px;
            right: 20px;
            font-size: 12px;
            color: #666;
        }
        .icon_button {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-left: 10px;
            white-space: nowrap;
            min-width: 140px;
        }
        .icon_button:hover {
            background-color: #0056b3;
        }
        .comment-section {
            display: none;
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .comment-display {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #B19CD9;
            border-radius: 5px;
        }
        .comment-input {
            width: 100%;
            max-width: 400px;
            height: 80px;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            resize: vertical;
        }
        .comment {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    <section class="hero">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="../index.php"><img src="../Assets/Images/Logo.png" alt="logo"></a>
                </div>
                <div class="navlinks">
                    <li><a href="../index.php">Home</a></li>
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

    <div class="post_container">
        <form method="POST" action="" enctype="multipart/form-data">
            <h3>What's on your mind...</h3>
            <textarea class="post_input" name="post_text" placeholder="Share your thoughts..." required></textarea>
            <label for="image" style="margin-top: 10px;">Choose a file:</label>
            <input type="file" id="image" name="image" accept="image/*" style="margin-top: 5px;"/> 
            <button type="submit" class="icon_button" style="margin-top: 10px;">Post</button>
        </form>
    </div>

    <?php while ($post = $posts->fetch_assoc()): ?>
        <div class="post_container">
            <div class="user_container">
                <img width="25px" src="../Assets/Images/profile.png" alt="Profile">
                <p><?php echo htmlspecialchars($post['username']); ?></p>
                <span class="post_date"><?php echo $post['created_at']; ?></span>
                <?php if ($post['username'] === $username): ?>
                    <a href="?delete_post=<?php echo $post['id']; ?>" class="icon_button">Delete</a>
                <?php endif; ?>
            </div>
            <div class="post">
                <h1><?php echo htmlspecialchars($post['post_text']); ?></h1>
                <?php if ($post['image_path']): ?>
                    <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                <?php endif; ?>
            </div>

            <?php
            // Fetch comments for the post
            $post_id = $post['id'];
            $comments = $conn->query("SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at ASC");
            $comment_count = $comments->num_rows;
            ?>
            <div>
                <button class="show-comments icon_button" onclick="toggleComments(<?php echo $post['id']; ?>)">
                    Show Comments (<?php echo $comment_count; ?>)
                </button>

                <div class="comment-section" id="comments-<?php echo $post['id']; ?>">
                    <form method="POST" action="">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <textarea name="comment_text" class="comment-input" placeholder="Add a comment..." required></textarea>
                        <button type="submit" class="icon_button">Comment</button>
                    </form>

                    <div class="comment-display">
                        <?php while ($comment = $comments->fetch_assoc()): ?>
                            <div class="comment">
                                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_text']); ?></p>
                                <?php if ($comment['username'] === $username): ?>
                                    <a href="?delete_comment=<?php echo $comment['id']; ?>" class="icon_button">Delete</a>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

    <footer>
        <div class="footer-container">
            <div class="footer-navlinks">
                <div class="left-links">
                    <li><a href="../index.php">Home</a></li>
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
                <a href="../index.php"><img src="../Assets/Images/Logo.png" alt="logo"></a>
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
$conn->close(); // Close the database connection
?>
