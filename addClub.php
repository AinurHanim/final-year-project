<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project"; // Your database name

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
header('Content-Type: application/json');

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
$kategori = $input['kategori'];
$namaKelab = $input['namaKelab'];

// Insert the new club into the newdatakoko table
$sql = "INSERT INTO newdatakoko (name, category) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $namaKelab, $kategori);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>