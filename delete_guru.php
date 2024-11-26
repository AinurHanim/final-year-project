<?php
$namaGuru = $_GET['namaGuru'];
$typeUnit = $_GET['typeUnit'];
$namaUnit = $_GET['namaUnit'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$stmt = $conn->prepare("DELETE FROM daftarunitbadan WHERE nama = ? AND typeUnit = ? AND namaUnit = ?");
$stmt->bind_param("sss", $namaGuru, $typeUnit, $namaUnit);

// Execute the query
if ($stmt->execute()) {
    // Close the statement
    $stmt->close();

    // Return a success response
    http_response_code(200);
} else {
    // Handle any errors that might occur
    http_response_code(500);
    echo json_encode(array('error' => 'Error deleting data'));
}

// Close the database connection
$conn->close();
?>