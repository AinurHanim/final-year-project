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

$input = json_decode(file_get_contents('php://input'), true);
$namaPelajarBaru = $input['namaPelajarBaru'];
$icPelajarBaru = $input['icPelajarBaru'];
$kelasPelajar = $input['kelasPelajar'];

$sql = "INSERT INTO datapelajar (NAMA, `NO K/P`, TING) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $namaPelajarBaru,$icPelajarBaru, $kelasPelajar );

if ($stmt->execute()){
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>