<?php
// Sample code for updating student data
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection parameters
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// fetch_daftaracara.php
$namaEvent = $_GET['namaEvent'];

// Assuming you have a database connection established
$stmt = $conn->prepare("SELECT namaEvent, tarikh, tempat, peringkat, registerBy FROM daftaracara WHERE namaEvent = ?");
$stmt->bind_param("s", $namaEvent);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

// Close the connection
$stmt->close();
$conn->close();
?>