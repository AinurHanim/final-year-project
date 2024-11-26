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
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Get the JSON data from the request
$data = json_decode(file_get_contents('php://input'), true);

// Extract the data
$typeUnit = $data['typeUnit'];
$namaUnit = $data['namaUnit'];
$nama = $data['nama']; // Use the teacher's name for the update

// Prepare the SQL update statement
$sql = "UPDATE daftarunitbadan SET `typeUnit` = ?, `namaUnit` = ? WHERE nama = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $typeUnit, $namaUnit, $nama); // "sss" means string, string, string

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]); // Log the error message
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>