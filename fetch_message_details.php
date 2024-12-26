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

// Get the message ID from the request
$id = $_GET['id'];

// Fetch the message details
$sql = "SELECT sender, message, timestamp FROM messages WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $message = $result->fetch_assoc();
    echo json_encode($message);
} else {
 echo json_encode(['error' => 'Message not found']);
}

// Close connection
$stmt->close();
$conn->close();
?>