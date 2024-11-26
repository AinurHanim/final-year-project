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

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT NAMA FROM datapelajar WHERE NAMA LIKE '%$query%' LIMIT 10";
    $result = $conn->query($sql);
    
    $suggestions = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $suggestions[] = $row;
        }
    }

    // Return suggestions as JSON
    echo json_encode($suggestions);
}

$conn->close();
?>
