<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project"; // Database name

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Sambungan ke pangkalan data gagal.']));
}

header('Content-Type: application/json');

// Decode JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate inputs
if (empty($input['namaGuruBaru']) || empty($input['emailGuruBaru'])) {
    echo json_encode(['success' => false, 'message' => 'Medan input tidak boleh kosong.']);
    exit;
}

// Assign inputs to variables
$namaGuruBaru = htmlspecialchars(strip_tags($input['namaGuruBaru']));
$emailGuruBaru = htmlspecialchars(strip_tags($input['emailGuruBaru']));

// Insert query
$sql = "INSERT INTO dataguru (`Nama Guru`, email) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Persediaan penyataan SQL gagal: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ss", $namaGuruBaru, $emailGuruBaru);

// Execute statement
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Ralat semasa menyimpan data: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
