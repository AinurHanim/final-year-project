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

$namaGuru = isset($_GET['namaGuru']) ? $_GET['namaGuru'] : '';

$sql = "SELECT nama, typeUnit, namaUnit FROM daftarunitbadan WHERE nama = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $namaGuru);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$stmt->close();
$conn->close();
?>
