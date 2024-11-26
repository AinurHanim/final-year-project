<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$unit = $_POST['unit'] ?? '';
error_log("Unit received: " . $unit); // Debugging line

$unitMapping = [
    'Sukan dan Permainan' => 'Sukan dan Permainan',
    'Kelab dan Persatuan' => 'Kelab dan Persatuan',
    'Badan beruniform' => 'Unit beruniform',
    'Lain-lain' => 'Lain-lain'
];

$selectedCategory = $unitMapping[$unit] ?? '';

if (empty($selectedCategory)) {
    die("Invalid unit category."); // Ensure this is not triggered
}

// Prepare the SQL query
$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, acara FROM updatepenyertaan WHERE kategori_badan_unit = ? AND registerBy = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $selectedCategory, $_SESSION['full_name']); // Correct the binding
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data); // Return the data as JSON

$stmt->close();
$conn->close();
?>