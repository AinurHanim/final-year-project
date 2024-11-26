<?php
// Sample code for fetching student data
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection parameters
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query
$query = $_GET['query'];

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT NAMA FROM datapelajar WHERE NAMA LIKE ?");
$likeQuery = "%$query%";
$stmt->bind_param("s", $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Return the data as JSON
echo json_encode($students);

// Close the connection
$stmt->close();
$conn->close();
?>