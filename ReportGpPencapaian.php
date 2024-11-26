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

$peringkatAcara = $_POST['peringkat'] ?? ''; // Get the selected peringkatAcara
error_log("Peringkat Acara received: " . $peringkatAcara); // Log received peringkatAcara

if (empty($peringkatAcara)) {
    die("Peringkat Acara is required.");
}

// Get the username of the logged-in user
$registerBy = $_SESSION['full_name'];

// Update the SQL query to filter by peringkatAcara and registerBy
$sql = "
    SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, acara 
    FROM updatepenyertaan 
    WHERE peringkatAcara = ? AND registerBy = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $peringkatAcara, $registerBy);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data); // Return the data as JSON

$stmt->close();
$conn->close();
?>