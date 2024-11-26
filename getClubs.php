<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project"; // Your database name

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
header('Content-Type: application/json');

// Fetch clubs from newdatakoko ```php
$sql = "SELECT * FROM newdatakoko";
$result = $conn->query($sql);

$clubs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clubs[] = $row;
    }
}

echo json_encode($clubs);

$conn->close();
?>