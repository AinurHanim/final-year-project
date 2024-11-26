<?php
session_start();
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentName = $_GET['studentName'] ?? '';
if ($studentName) {
    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT `NO K/P` FROM datapelajar WHERE NAMA = ?"); // Ensure the table and column names are correct
    $stmt->bind_param("s", $studentName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['icNumber' => $row['NO K/P']]);
    } else {
        echo json_encode(['icNumber' => null]); // No data found
    }
} else {
    echo json_encode(['icNumber' => null]); // No student name provided
}

$stmt->close();
$conn->close();
?>