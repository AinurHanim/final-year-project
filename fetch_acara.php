<?php
session_start();

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

$sql = "SELECT namaEvent, tarikh, tempat FROM daftaracara";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'namaEvent' => $row['namaEvent'],
        'tarikh' => $row['tarikh'],
        'tempat' => $row['tempat']
    ];
}

// Output the events as JSON to be consumed by your JavaScript
echo json_encode($events);

// Close connections
$stmt->close();
$conn->close();
?>