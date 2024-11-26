<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = trim($_GET['query']);
$sql = "SELECT NAMA FROM datapelajar WHERE NAMA LIKE CONCAT('%', ?, '%')"; // Use LIKE for partial matches
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $query);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row['NAMA']; // Collect the names
}

echo json_encode($suggestions); // Return the results as JSON
$stmt->close();
$conn->close();
?>