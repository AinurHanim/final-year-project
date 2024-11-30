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

// Retrieve the email and password from the form submission
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare the SQL statement to check if the email exists
$check_stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // Email exists, update the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $update_stmt = $conn->prepare("UPDATE register SET password = ? WHERE email = ?");
    $update_stmt->bind_param("ss", $passwordHash, $email); // Bind the password and email parameters

    if ($update_stmt->execute()) {
        if ($update_stmt->affected_rows > 0) {
            echo 'Berjaya';
        } else {
            echo 'E-mel tidak ditemui atau tiada perubahan dibuat';
        }
    } else {
        echo 'SQL Error: ' . $conn->error; // Print any SQL error
    }

    $update_stmt->close(); // Close the update statement
} else {
    echo 'E-mel tidak ditemui ';
}

$check_stmt->close(); // Close the check statement
$conn->close(); // Close the database connection
?>