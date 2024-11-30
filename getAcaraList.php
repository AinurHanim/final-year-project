<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT namaEvent, tarikh, tempat FROM daftaracara"; // Adjust table name and column
$result = $conn->query($sql);

$acaraList = array();
while($row = $result->fetch_assoc()) {
    $acaraList[] = $row;
}

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

if (empty($acaraList)) {
    echo json_encode(['message' => 'Tiada acara ditemui']);
    exit;
}

echo json_encode($acaraList);
$conn->close();
?>