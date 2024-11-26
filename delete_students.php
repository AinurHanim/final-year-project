<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request body
$data = json_decode(file_get_contents("php://input"), true);

// Check if data was received
if (is_array($data) && !empty($data)) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("DELETE FROM updatepenyertaan WHERE nama_pelajar = ? AND no_ic = ? AND acara = ?");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    // Loop through each student to delete
    foreach ($data as $student) {
        // Extract the event name from the student data
        $acara = $student['acara']; // Assuming 'acara' is included in the student data

        // Bind parameters and execute the statement
        $stmt->bind_param("sss", $student['nama_pelajar'], $student['no_ic'], $acara);
        $stmt->execute();
    }

    // Close the statement
    $stmt->close();

    // Respond with success
    echo json_encode(['success' => true]);
} else {
    // Respond with an error
    echo json_encode(['success' => false, 'error' => 'No valid data received']);
}

// Close the database connection
$conn->close();
?>