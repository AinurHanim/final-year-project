<?php
// Sample code for updating student data
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection parameters
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Validate incoming data
if (isset($data['pencapaian']) && isset($data['nama_pelajar'])) {
    $pencapaian = $conn->real_escape_string($data['pencapaian']);
    $nama_pelajar = $conn->real_escape_string($data['nama_pelajar']);

    // Prepare the SQL statement to update the record
    $sql = "UPDATE updatepenyertaan SET pencapaian='$pencapaian' WHERE nama_pelajar='$nama_pelajar'";

    // Execute the statement
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
}

// Close the database connection
$conn->close();
?>