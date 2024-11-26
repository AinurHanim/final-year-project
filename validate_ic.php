<?php
session_start();
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

$conn = new mysqli($servername, $dbusername , $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ic = $_GET['ic'] ?? '';

$valid = false;

if ($ic) {
    $stmt = $conn->prepare("SELECT `NO K/P` FROM datapelajar WHERE `NO K/P` = ?"); // Validate IC
    $stmt->bind_param("s", $ic);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $valid = true;
    }
}

echo json_encode(['valid' => $valid]);

$stmt->close();
$conn->close();
?>