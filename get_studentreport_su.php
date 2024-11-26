<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "project";  // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from 'updatepenyertaan' table
$sql = "SELECT `nama_pelajar` FROM `updatepenyertaan`";  // Correct table and column
$result = $conn->query($sql);

$students = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;  // Add each student name to the array
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($students);  // Return the data as JSON
?>
