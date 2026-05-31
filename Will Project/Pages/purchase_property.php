<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create connection
$conn = new mysqli("sql311.infinityfree.com", "if0_37309654", "Casperw777", "if0_37309654_RealHomeDataBase");

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Validate required GET parameters
if (isset($_GET['name'], $_GET['pty_id'])) {
    $pty_name = urldecode($_GET['name']);
    $pty_id = urldecode($_GET['pty_id']);
} else {
    die(json_encode(["error" => "'name' or 'pty_id' not provided"]));
}

// Initialize purchase variable
$purchase = null;

// Check if user is logged in before attempting to delete
if (isset($_SESSION['user']) && $_SESSION['user'] !== 'guest') {
    // Prepare the DELETE statement
    $stmt = $conn->prepare("DELETE FROM properties WHERE property_name = ? AND property_id = ?");
    $stmt->bind_param("si", $pty_name, $pty_id); // Assuming property_id is an integer

    if (!$stmt->execute()) {
        die(json_encode(["error" => "Delete failed: " . $stmt->error]));
    } else {
        $purchase = 'success'; // Set purchase to success if deletion was successful

        // Update the property_name in the favourites table
        $updateStmt = $conn->prepare("UPDATE favourites SET status = 'unavailable' WHERE property_name = ?");
        $updateStmt->bind_param("s", $pty_name);

        if (!$updateStmt->execute()) {
            die(json_encode(["error" => "Update failed: " . $updateStmt->error]));
        }

        $updateStmt->close(); // Close the update statement
    }

    $stmt->close(); // Close the delete statement
} else {
    die(json_encode(["error" => "Invalid user session"]));
}

// Create a hashed access key
$hexKey = password_hash($pty_name, PASSWORD_DEFAULT);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Finalisation</title>
    <link rel="stylesheet" href="/css/landing_page.css">
    <link rel="stylesheet" href="/css/main_styles.css">
    <style>
        .payment-card {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px auto;
            text-align: center;
            width: 60%;
            height: 40%;
        }
        .card-content { margin-bottom: 15px; }
        .card-title { font-size: 24px; margin-bottom: 10px; color: #236BA3; }
        .due-message { font-size: 18px; margin-bottom: 10px; }
        .property-name { font-size: 16px; }
        .hex-key { font-weight: bold; color: #FF5733; }
        .card-footer { margin-top: 15px; }
        .btnPay {
            background-color: #236BA3;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btnPay:hover { background-color: #1a4f7c; }
    </style>
</head>
<body>
    <img class="background_image" src="/images/backgrounds/subtle_stars.png" alt="Background Image">
    <section>
        <div class="main_page">
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

            <div class="section1_body">
                <?php if ($purchase === 'success'): ?>
                    <div class="payment-card" id="paymentcard">
                        <div class="card-content">
                            <h2 class="card-title">Payment Due</h2>
                            <p class="due-message">Your payment is due within <strong>24 hours</strong>.</p>
                            <p class="property-name">Property Access key: <span class="hex-key">#<?= htmlspecialchars($hexKey) ?></span></p>
                        </div>
                        <div class="card-footer">
                            <button id="payment" class="btnPay">Pay Now</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
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
        document.getElementById('payment').addEventListener('click', function() {
            const paymentCard = document.getElementById('paymentcard');
            paymentCard.innerHTML = '<div class="card-content"><h2 class="card-title">Payment Success</h2><p>Your payment has been successfully processed. Enjoy your new property.</p><a class="btnMain" href="/index.php">Go to Home</a></div>';
        });
    </script>
</body>
</html>
