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

// Get the teacher's name from the request
$guruName = isset($_GET['name']) ? $conn->real_escape_string($_GET['name']) : '';

// Prepare the SQL query
$sql = "SELECT namaEvent, registerBy FROM daftaracara WHERE registerBy = '$guruName'";

// Execute the query
$result = $conn->query($sql);

// Initialize an array to hold the events
$events = [];

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch all events
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

// Close the database connection
$conn->close();

// Return the events as JSON
header('Content-Type: application/json');
echo json_encode($events);

?>