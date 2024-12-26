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

// Get the POST data
$kepada = $_POST['kepada'];
$mesej = $_POST['mesej'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO messages (sender, recipient, message, timestamp) VALUES (?, ?, ?, NOW())");
$sender = 'PK KOKO'; // Hardcoded sender for this example
$stmt->bind_param("sss", $sender, $kepada, $mesej);

// Execute the statement
if ($stmt->execute()) {
    echo "Message sent successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$conn->close();
?>