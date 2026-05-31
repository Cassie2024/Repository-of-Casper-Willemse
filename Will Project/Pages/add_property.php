<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("sql311.infinityfree.com", "if0_37309654", "Casperw777", "if0_37309654_RealHomeDataBase");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $property_name = $conn->real_escape_string($_POST['property_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $country = $conn->real_escape_string($_POST['country']);
    $planet = $conn->real_escape_string($_POST['planet']);
    $price = $conn->real_escape_string($_POST['price']);
    $property_type = $conn->real_escape_string($_POST['property_type']);
    $bedrooms = $conn->real_escape_string($_POST['bedrooms']);
    $bathrooms = $conn->real_escape_string($_POST['bathrooms']);
    $livingrooms = $conn->real_escape_string($_POST['livingrooms']);
    $kitchens = $conn->real_escape_string($_POST['kitchens']);
    $garages = $conn->real_escape_string($_POST['garages']);
    $description = $conn->real_escape_string($_POST['description']);
    $agent = $conn->real_escape_string($_POST['agent']);

    $uploadDir = '../images/properties/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadStatus = "";
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

    if (isset($_FILES['img'])) {
        if ($_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['img']['name']);
            $file_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $file_name);
            $target_file = $uploadDir . $file_name;

            if (in_array($_FILES['img']['type'], $allowedTypes)) {
                if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
                    $imgPath = basename($target_file);
                    $uploadStatus .= "File uploaded successfully: " . htmlspecialchars($file_name) . ". ";

                    $stmt = $conn->prepare("INSERT INTO properties (property_id, property_name, address, city, state, country, planet, price, property_type, bathrooms, bedrooms, kitchens, livingrooms, garages, img, description, prospective_customer, agent, created_at) VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, null, ?, null)");
                    if ($stmt) {
                        $stmt->bind_param("ssssssssssssssss", $property_name, $address, $city, $state, $country, $planet, $price, $property_type, $bathrooms, $bedrooms, $kitchens, $livingrooms, $garages, $imgPath, $description, $agent);

                        $encoded_property_name = urlencode($property_name);
                        if ($stmt->execute()) {
                            echo json_encode(["success" => "Property added successfully"]);
                            header("Location: /Pages/property_description.php?name=$encoded_property_name");
                            exit();
                        } else {
                            echo json_encode(["error" => "Error adding property: " . $stmt->error]);
                            header("Location: /index.php");
                            exit();
                        }
                    } else {
                        echo json_encode(["error" => "Statement preparation failed: " . $conn->error]);
                        exit();
                    }
                } else {
                    $uploadStatus .= "Failed to upload file: " . htmlspecialchars($file_name) . ". ";
                }
            } else {
                $uploadStatus .= "File type not allowed: " . htmlspecialchars($file_name) . ". ";
            }
        } else {
            $uploadStatus .= "Error with file upload: " . $_FILES['img']['error'] . ". ";
        }
    } else {
        $uploadStatus .= "No file uploaded. ";
    }

    echo json_encode(["upload_status" => $uploadStatus]);
}
$conn->close();
?>
