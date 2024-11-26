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

// Retrieve the 'nama' parameter from the GET request
$nama = $_GET['nama_pelajar']; // Use GET for the search
$registerBy = $_SESSION["username"]; // Assuming the username is stored in the session

// Prepare and execute the SQL query
$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, acara FROM updatepenyertaan WHERE registerBy=? AND nama_pelajar=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $registerBy, $nama); // Bind both parameters
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data[] = array("nama_pelajar" => "", "kategori_badan_unit" => "", "pencapaian" => "", "registerBy" => "", "acara" => ""); // Indicate no results found
}

// Return the results as JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>