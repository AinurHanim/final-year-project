<?php
include('database_connection.php');
// Assuming connection to the database has been established
$query = $_GET['query'];

$stmt = $conn->prepare("SELECT NAMA FROM datapelajar WHERE namapelajar LIKE ? LIMIT 10");
$searchTerm = "%".$query."%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = $row;
}

echo json_encode($suggestions);
?>
