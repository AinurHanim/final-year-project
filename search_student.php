<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$currentUser  = $_SESSION['full_name']; // User's full name from session

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentName = trim($_GET['name']);
$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, acara FROM updatepenyertaan WHERE nama_pelajar = ? AND registerBy = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $studentName, $currentUser );
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row; // Collect all matching records
}

// Debugging: Check if any records were found
if (empty($students)) {
    error_log("No records found for student: $studentName registered by $currentUser ");
} else {
    error_log("Records found: " . print_r($students, true));
}

echo json_encode($students); // Return the results as JSON
$stmt->close();
$conn->close();
?>