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

// Get the updated data from the request
$nama_pelajar = $_POST['nama_pelajar'];
$no_ic = $_POST['no_ic'];
$kelas = $_POST['kelas'];
$pencapaian = $_POST['pencapaian'];
$kategori_badan_unit = $_POST['kategori_badan_unit'];
$nama_kelab = $_POST['nama_kelab'];

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE updatepenyertaan SET no_ic = ?, kelas = ?, pencapaian = ?, kategori_badan_unit = ?, nama_kelab = ? WHERE nama_pelajar = ?");
$stmt->bind_param("ssssss", $noIc, $kelas, $pencapaian, $kategoriBadanUnit, $namaKelab, $namaPelajar);
$stmt->execute();

// Return a success message
echo json_encode(['message' => 'Maklumat pelajar telah berjaya dikemaskini']);

// Close the connection
$stmt->close();
$conn->close();
?>