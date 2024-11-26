<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection parameters
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the query from the request
$query = $_GET['query'];

// Check if the query is a number
if (is_numeric($query)) {
    // Query the database to get the relevant suggestions
    $sql = "SELECT `NO K/P` AS noIcPelajar FROM datapelajar WHERE `NO K/P` LIKE ?"; // Adjust table name and column as necessary
    $stmt = $conn->prepare($sql);
    $param = $query . '%'; // Use LIKE operator with wildcard
    $stmt->bind_param("s", $param); // Bind the parameter
    $stmt->execute();
    $result = $stmt->get_result();

    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }
} else {
    // If the query is not a number, return an empty array
    $suggestions = [];
}

// Return the suggestions as JSON
echo json_encode($suggestions);

// Close the statement and the database connection
$stmt->close();
$conn->close();
?>