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

// Retrieve the email from the form submission
$email = $_POST['email'];

// Prepare the SQL statement to check if the email exists
$stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$stmt->bind_param("s", $email); // "s" indicates that the parameter is a string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Email exists, reset the password column
    $update_stmt = $conn->prepare("UPDATE register SET password = '' WHERE email = ?");
    $update_stmt->bind_param("s", $email); // Bind the email parameter
    $update_stmt->execute();
    
    // Check if the update was successful
    if ($update_stmt->affected_rows > 0) {
        echo 'Berjaya';
        
    } else {
        echo 'Gagal menetapkan semula kata laluan';
    }
    
    $update_stmt->close(); // Close the update statement
} else {
    // Email does not exist
    echo 'Tiada rekod dalam data';
}

$stmt->close(); // Close the select statement
$conn->close(); // Close the database connection
?>