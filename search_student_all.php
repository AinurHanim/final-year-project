<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$currentUser   = $_SESSION['full_name']; // User's full name from session

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentName = trim($_GET['name']);
$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, registerBy, acara FROM updatepenyertaan WHERE nama_pelajar = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $studentName);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row; // Collect all matching records
}

echo json_encode($students); // Return the results as JSON
$stmt->close();
$conn->close();
?>