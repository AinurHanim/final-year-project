<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$event = $_GET['event'] ?? '';

$sql = "SELECT kategoriUnit, namaKelab, peringkat FROM daftaracara WHERE namaEvent = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $event);
$stmt->execute();
$result = $stmt->get_result();

$data = $result->fetch_assoc();

echo json_encode($data);

$stmt->close();
$conn->close();
?>