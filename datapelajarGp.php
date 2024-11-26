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

// Get the search query and the logged-in teacher's username
$query = $_GET['query'];
$registerBy = $_SESSION["username"];

// Prepare and execute the SQL query to fetch student names registered by the logged-in teacher
$sql = "SELECT DISTINCT nama_pelajar FROM updatepenyertaan WHERE nama_pelajar LIKE ? AND registerBy = ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param('ss', $searchTerm, $registerBy);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return the results as JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>