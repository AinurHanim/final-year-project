<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['mylogin']) || !isset($_SESSION['userType'])) {
    header("Location: login.html");
    exit();
}

// Get the user's full name from the session
$fullName = $_SESSION['full_name'];
?>