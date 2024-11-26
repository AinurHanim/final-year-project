<?php
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
error_log("Unit received: " . $unit);

$unitMapping = [
    'Sukan dan Permainan' => 'Sukan dan Permainan',
    'Kelab dan Persatuan' => 'Kelab dan Persatuan',
    'Unit Beruniform' => 'Unit Beruniform',
    'Lain-lain' => 'Lain-lain'
];

$selectedCategory = $unitMapping[$unit] ?? '';

if (empty($selectedCategory)) {
    die("Invalid unit category.");
}

$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, registerBy, acara FROM updatepenyertaan WHERE kategori_badan_unit = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selectedCategory);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>