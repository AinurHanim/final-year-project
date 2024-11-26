<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_GET['query']) ? $_GET['query'] : '';
$sql = "SELECT `Nama Guru` FROM dataguru WHERE `Nama Guru` LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$likeQuery = "%" . $query . "%";
$stmt->bind_param("s", $likeQuery);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row;
}

echo json_encode($suggestions);
$stmt->close();
$conn->close();
?>
