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

if (isset($_GET['namaAcara'])) {
    $namaAcara = $conn->real_escape_string($_GET['namaAcara']);
    $sql = "SELECT * FROM daftaracara WHERE namaEvent LIKE '%$namaAcara%' LIMIT 1";// Fetch only one event
    $result = $conn->query($sql);

    if ($row = $result->fetch_assoc()) {
        echo json_encode($row); // Return a single event as an object
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close();
?>