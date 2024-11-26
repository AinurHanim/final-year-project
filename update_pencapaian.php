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

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

$nama_pelajar = $conn->real_escape_string($data['nama_pelajar']);
$no_ic = $conn->real_escape_string($data['no_ic']);
$kelas = $conn->real_escape_string($data['kelas']);
$pencapaian = $conn->real_escape_string($data['pencapaian']);

// Prepare the SQL statement to update the record
$sql = "UPDATE updatepenyertaan SET pencapaian='$pencapaian' WHERE nama_pelajar='$nama_pelajar' AND no_ic='$no_ic'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating record: ' . $conn->error]);
}

$conn->close();
?>