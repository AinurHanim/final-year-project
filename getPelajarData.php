<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['namaPelajar'])) {
    $namaPelajar = $conn->real_escape_string($_GET['namaPelajar']);
    $sql = "SELECT nama_pelajar, no_ic, kelas, pencapaian, acara, kategori_badan_unit, nama_kelab FROM updatepenyertaan WHERE nama_pelajar = '$namaPelajar'";
    $result = $conn->query($sql);
    
    $data = array();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    // Return data as JSON
    echo json_encode($data);
}

$conn->close();
?>