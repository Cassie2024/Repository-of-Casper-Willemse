<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['resetEmail'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    if (empty($email) || empty($newPassword)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
        exit;
    }

    // Database connection
    $servername = "fdb1029.awardspace.net";
    $db_username = "4530757_clientsdata";
    $db_password = "6g4D9LErufCJVgc";
    $dbname = "4530757_clientsdata";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
        exit;
    }

    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateSql = "UPDATE users SET password = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ss", $hashedNewPassword, $email);
        
        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Password reset successful.', 'redirect' => 'login.php']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update password.']);
        }
        $updateStmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found with that email.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}