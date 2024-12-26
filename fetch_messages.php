<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch messages for Setiausaha
$sql = "SELECT id, sender, message, timestamp FROM messages WHERE recipient = 'Setiausaha' ORDER BY timestamp DESC";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Return messages as JSON
header('Content-Type: application/json');
echo json_encode($messages);

// Close connection
$conn->close();
?>