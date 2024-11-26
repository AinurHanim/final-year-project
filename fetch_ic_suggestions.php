<?php

error_log("Query received: " . $query); // Log the query received
session_start();
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'] ?? '';
$suggestions = [];

if ($query) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT `NO K/P` FROM datapelajar WHERE `NO K/P` LIKE CONCAT(?, '%')"); // Fetch suggestions
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['NO K/P'];
    }
}

echo json_encode($suggestions);

$stmt->close();
$conn->close();
?>