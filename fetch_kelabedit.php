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

// Get the selected category from the request
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';

// Determine the column to query based on the selected category
switch($kategori) {
    case 'SUKAN DAN PERMAINAN':
        $column = 'SUKAN_DAN_PERMAINAN'; // Ensure this matches your database column name
        break;
    case 'PERSATUAN (AKADEMIK)':
        $column = 'PERSATUAN_AKADEMIK'; // Ensure this matches your database column name
        break;
    case 'KELAB (BUKAN AKADEMIK)':
        $column = 'KELAB_BUKAN_AKADEMIK'; // Ensure this matches your database column name
        break;
    case 'BADAN BERUNIFORM':
        $column = 'BADAN_BERUNIFORM'; // Ensure this matches your database column name
        break;
    default:
        $column = '';
}

// Query the database to get the relevant kelab data
if ($column) {
    $query = "SELECT DISTINCT `$column` AS namaUnit FROM datakoko WHERE `$column` IS NOT NULL AND `$column` != ''";
    $result = $conn->query($query);

    $kelabOptions = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $kelabOptions[] = $row;
        }
    }

    // Return the options as JSON
    echo json_encode($kelabOptions);
} else {
    echo json_encode([]); // Return an empty array if no valid column was found
}

// Close the connection
$conn->close();
?>