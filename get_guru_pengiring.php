<?php
header('Content-Type: application/json');

$query = $_GET['query'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$stmt = $conn->prepare("SELECT `Nama Guru` FROM dataguru WHERE LOWER(`Nama Guru`) LIKE ?");
$param = '%' . strtolower($query) . '%';
$stmt->bind_param("s", $param);

// Execute the query
if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row['Nama Guru'];
    }

    // Close the statement
    $stmt->close();

    // Return the data in JSON format
    echo json_encode($data);
} else {
    // Handle any errors that might occur
    echo "Error: " . $stmt->error;
}

// Close the database connection
$conn->close();
?>