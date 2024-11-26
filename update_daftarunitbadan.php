<?php
// Enable error reporting for debugging
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
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Validate incoming data
if (!isset($data['nama']) || !isset($data['typeUnit']) || !isset($data['namaUnit'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
    exit;
}

$nama = $data['nama'];
$typeUnit = $data['typeUnit'];
$namaUnit = $data['namaUnit'];

// Prepare the SQL UPDATE statement
$sql = "UPDATE daftarunitbadan SET typeUnit = ?, namaUnit = ? WHERE nama = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]));
}

$stmt->bind_param("sss", $typeUnit, $namaUnit, $nama);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Data updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error updating data: ' . $stmt->error]);
}

// Close the connection
$stmt->close();
$conn->close();
?>