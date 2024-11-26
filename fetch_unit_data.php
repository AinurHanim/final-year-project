<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get full_name from the query parameter
$full_name = $_GET['full_name'] ?? '';

if ($full_name) {
    // Query to fetch typeUnit and namaUnit from daftarunitbadan table
    $sql = "SELECT typeUnit, namaUnit FROM daftarunitbadan WHERE guruPenasihat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $full_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Output the data as JSON
        echo json_encode([
            'typeUnit' => $row['typeUnit'],
            'namaUnit' => $row['namaUnit']
        ]);
    } else {
        // If no matching records, return empty fields
        echo json_encode([
            'typeUnit' => '',
            'namaUnit' => ''
        ]);
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
