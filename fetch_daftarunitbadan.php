<?php
$query = $_GET['query'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the guru name from the query parameter
$guru_name = $_GET['nama'];

// Prepare the SQL query
$stmt = $conn->prepare("SELECT * FROM daftarunitbadan WHERE nama = ?");
$stmt->bind_param("s", $guru_name);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch the data
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close the statement
$stmt->close();

// Close the database connection
$conn->close();

// Return the data in JSON format
header('Content-Type: application/json');
echo json_encode($data);

?>