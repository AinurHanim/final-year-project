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

// Query to get all distinct units from all relevant columns
$query = "
    SELECT DISTINCT `SUKAN DAN PERMAINAN` AS namaUnit FROM datakoko WHERE `SUKAN DAN PERMAINAN` IS NOT NULL AND `SUKAN DAN PERMAINAN` != ''
    UNION
    SELECT DISTINCT `BADAN BERUNIFORM` AS namaUnit FROM datakoko WHERE `BADAN BERUNIFORM` IS NOT NULL AND `BADAN BERUNIFORM` != ''
    UNION
    SELECT DISTINCT `PERSATUAN (AKADEMIK)` AS namaUnit FROM datakoko WHERE `PERSATUAN (AKADEMIK)` IS NOT NULL AND `PERSATUAN (AKADEMIK)` != ''
    UNION
    SELECT DISTINCT `KELAB (BUKAN AKADEMIK)` AS namaUnit FROM datakoko WHERE `KELAB (BUKAN AKADEMIK)` IS NOT NULL AND `KELAB (BUKAN AKADEMIK)` != ''
";

$result = $conn->query($query);

$unitOptions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unitOptions[] = $row;
    }
}

// Return the options as JSON
echo json_encode($unitOptions);

// Close the connection
$conn->close();
?>
