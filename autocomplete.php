<?php
$servername = "localhost"; // Update with your server details
$username = "username"; // Update with your username
$password = ""; // Update with your password
$dbname = ""; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$type = $_GET['type'];
$query = $_GET['query'];

$sql = "SELECT * FROM datapelajar WHERE Nama LIKE ? OR NO_KP LIKE ? OR TING LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $query . "%";
$stmt->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    switch ($type) {
        case 'namaPelajar':
            $suggestions[] = $row['Nama'];
            break;
        case 'noIc':
            $suggestions[] = $row['NO_KP'];
            break;
        case 'kelas':
            $suggestions[] = $row['TING'];
            break;
    }
}

echo json_encode($suggestions);

$stmt->close();
$conn->close();
?>
