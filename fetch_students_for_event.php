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

if (!isset($_GET['event'])) {
    die("Event parameter not set.");
}

$event = $_GET['event'];

// Prepare and execute the query
$query = "SELECT nama_pelajar, no_ic, kelas, pencapaian FROM updatepenyertaan WHERE acara = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}

$stmt->bind_param("s", $event);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row; // Add each student to the array
}

// Debugging output
if (empty($students)) {
    error_log("No students found for event: " . $event); // Log if no students found
} else {
    error_log("Students found: " . print_r($students, true)); // Log the fetched students
}

echo json_encode($students); // Return the JSON response
?>