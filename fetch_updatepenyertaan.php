<?php
// Sample code for fetching student data from updatepenyertaan table
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

// Get the student name from the query parameter
$nama_pelajar = $_GET['nama_pelajar'];

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT nama_pelajar, no_ic, kelas, pencapaian, acara, kategori_badan_unit, nama_kelab FROM updatepenyertaan WHERE nama_pelajar LIKE ?");
$likeQuery = "%$nama_pelajar%";
$stmt->bind_param("s", $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Return the data as JSON
echo json_encode($students);

// Close the connection
$stmt->close();
$conn->close();
?>