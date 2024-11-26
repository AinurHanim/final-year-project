<?php
// Database connection
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "";  // Replace with your DB password
$dbname = "project";  // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the selected student name from the URL parameters
$nama_pelajar = isset($_GET['nama_pelajar']) ? $_GET['nama_pelajar'] : '';

if ($nama_pelajar != '') {
    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM updatepenyertaan WHERE nama_pelajar = ?");
    $stmt->bind_param("s", $nama_pelajar);  // Bind the student name
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;  // Add each row of data to the array
    }

    $stmt->close();
    $conn->close();

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // If no name is provided, return an empty array
    echo json_encode([]);
}
?>
