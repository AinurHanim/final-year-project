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

$acara = $_POST['acara'];

if (empty($acara)) {
    echo json_encode(['error' => 'No acara selected']);
    exit;
}

// Fetch event details from daftaracara
$sql = "SELECT namaEvent, tarikh, tempat, peringkat, registerBy, kategoriUnit, namaKelab FROM daftaracara WHERE namaEvent = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $acara);
$stmt->execute();
$result = $stmt->get_result();
$eventDetails = $result->fetch_assoc();

// Fetch participants from updatepenyertaan
$sql = "SELECT nama_pelajar FROM updatepenyertaan WHERE acara = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $acara);
$stmt->execute();
$result = $stmt->get_result();
$participants = [];
while ($row = $result->fetch_assoc()) {
    $participants[] = $row;
}

// Return the data in JSON format
echo json_encode(['eventDetails' => $eventDetails, 'participants' => $participants]);

$conn->close();
?>