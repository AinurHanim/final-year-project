<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize the email from the form submission
$email = trim($_POST['email']); // Trim to remove extra spaces

if (empty($email)) {
    echo 'empty_email'; // Return specific error for empty email
    exit;
}

// Prepare the SQL statement to check if the email exists
$stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email exists, reset the password column
    $update_stmt = $conn->prepare("UPDATE register SET password = '' WHERE email = ?");
    $update_stmt->bind_param("s", $email);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        echo 'success'; // Password reset successfully
    } else {
        echo 'reset_failed'; // Password reset failed
    }

    $update_stmt->close();
} else {
    echo 'not_found'; // Email not found in the database
}

$stmt->close();
$conn->close();
