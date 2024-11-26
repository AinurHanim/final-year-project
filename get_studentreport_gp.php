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

// Retrieve the logged-in user's identifier from the session
$registerBy = $_SESSION["username"];

// Prepare and execute the SQL query
$sql = "SELECT DISTINCT nama_pelajar FROM updatepenyertaan WHERE registerBy=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $registerBy); // Bind the parameter
$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data[] = array("nama_pelajar" => ""); // Add an empty row to indicate no results found
}

// Return the results as JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>
