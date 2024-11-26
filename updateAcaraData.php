<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['namaAcara'], $data['tarikh'], $data['tempat'], $data['peringkat'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data.']);
    exit;
}

$stmt = $conn->prepare("UPDATE daftaracara SET namaEvent = ?, tarikh = ?, tempat = ?, peringkat = ? WHERE namaEvent = ?");
$stmt->bind_param("sssss", $data['namaAcara'], $data['tarikh'], $data['tempat'], $data['peringkat'], $data['namaAcara']);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database update failed.']);
}

$stmt->close();
$conn->close();
?>
