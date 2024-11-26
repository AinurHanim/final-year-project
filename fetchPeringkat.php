<?php
// fetchPeringkat.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming you have a POST request with 'acara' sent from the frontend
$acara = $_POST['acara'] ?? '';

if (empty($acara)) {
    die("Acara is required.");
}

// SQL query to fetch peringkat based on acara
$sql = "SELECT peringkat FROM daftaracara WHERE namaEvent = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $acara);
$stmt->execute();
$result = $stmt->get_result();

$peringkat = null;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $peringkat = $row['peringkat'];
}

// Return the peringkat to the frontend
echo json_encode(['peringkat' => $peringkat]);

$stmt->close();
$conn->close();
?>