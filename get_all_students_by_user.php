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

// Get the full name from the session
$registerBy = trim($_SESSION["full_name"]); // Change this to the correct session variable for the full name
error_log("Current user from session: " . (isset($registerBy) ? $registerBy : "Session not set")); // Log current user

// Prepare and execute the SQL query
$sql = "SELECT nama_pelajar, kategori_badan_unit, nama_kelab, pencapaian, acara FROM updatepenyertaan WHERE registerBy=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $registerBy);

if ($stmt->execute() === false) {
    error_log("SQL error: " . $stmt->error);
    echo json_encode(["error" => "SQL execution failed."]);
    exit;
}

$result = $stmt->get_result();
error_log("Executed query: " . $sql . " with username: " . $registerBy);
error_log("Number of results: " . $result->num_rows);

$data = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    error_log(print_r($data, true)); // Log the fetched data
} else {
    error_log("No results found for user: " . $registerBy);
}

// Return the results as JSON
echo json_encode($data);

$stmt->close();
$conn->close();
?>