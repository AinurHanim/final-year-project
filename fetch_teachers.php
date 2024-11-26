<?php
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

// Get the selected namaKelab from the request
$namaKelab = $_GET['namaKelab'];

// Query the database to get the relevant teacher data
$sql = "SELECT nama FROM daftarunitbadan WHERE namaUnit = ?"; // Adjust table name and column as necessary
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $namaKelab);
$stmt->execute();
$result = $stmt->get_result();

$teachers = [];
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

// Return the teacher data as JSON
echo json_encode($teachers);

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>