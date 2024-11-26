<?php
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "project"; // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_POST['query']; // Use POST to get the query

// Prepare the SQL query
$stmt = $conn->prepare("SELECT NAMA FROM datapelajar WHERE NAMA LIKE ?");
$param = $query . '%';
$stmt->bind_param("s", $param);

// Execute the query
if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row['NAMA']; // Store names in the array
    }

    // Close the statement
    $stmt->close();

    // Return the data in JSON format
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Handle any errors that might occur
    echo "Error: " . $stmt->error;
}

// Close the database connection
$conn->close();
?>