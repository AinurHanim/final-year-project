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

$nama = $_POST['nama'];
error_log("Nama received: " . $nama); // Log received name

// Prepare and execute the SQL query
$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, registerBy, acara 
        FROM updatepenyertaan 
        WHERE nama_pelajar=?"; // Ensure 'nama_kelab' is included in the SELECT
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $nama);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Return the results as JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>