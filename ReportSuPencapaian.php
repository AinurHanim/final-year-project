<?php
// ReportSuPencapaian.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pencapaian = $_POST['pencapaian'] ?? '';
if (empty($pencapaian)) {
    die("Pencapaian is required.");
}

// SQL query to join updatepenyertaan and daftaracara
$sql = "
    SELECT 
        u.nama_pelajar, 
        u.kategori_badan_unit, 
        u.nama_kelab, 
        u.pencapaian, 
        u.registerBy, 
        u.acara, 
        d.peringkat 
    FROM 
        updatepenyertaan u 
    JOIN 
        daftaracara d ON u.acara = d.namaEvent 
    WHERE 
        d.peringkat = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $pencapaian);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return the data as JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>