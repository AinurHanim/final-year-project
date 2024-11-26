<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['mylogin']) || !isset($_SESSION['full_name'])) {
    header("Location: login.html");
    exit();
}

// Get the user's full name from the session
$fullName = $_SESSION['full_name'];

// Output the full name as JSON
echo json_encode(array('full_name' => $fullName));
?>
