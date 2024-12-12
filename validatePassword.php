<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Ensure user is logged in
    http_response_code(401);
    echo 'unauthorized';
    exit();
}

$username = $_SESSION['username']; // Username of the currently logged-in user
$inputPassword = $_POST['password'] ?? '';

$host = 'localhost';
$db = 'project';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the hashed password from the database
$stmt = $conn->prepare("SELECT password FROM register WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($storedPasswordHash);
$stmt->fetch();
$stmt->close();
$conn->close();

// Verify the input password against the hashed password in the database
if (password_verify($inputPassword, $storedPasswordHash)) {
    echo 'valid';
} else {
    echo 'invalid';
}
?>
