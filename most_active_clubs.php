<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "project";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to find the top 3 active clubs
$sqlTopClubs = "
    SELECT namaKelab, COUNT(*) AS totalActivities 
    FROM daftaracara 
    GROUP BY namaKelab 
    ORDER BY totalActivities DESC 
    LIMIT 3";
$resultTopClubs = $conn->query($sqlTopClubs);

$topClubs = [];
if ($resultTopClubs->num_rows > 0) {
    while ($row = $resultTopClubs->fetch_assoc()) {
        $clubName = $row['namaKelab'];
        $detailsQuery = "
            SELECT kategoriUnit, namaEvent, peringkat, registerBy 
            FROM daftaracara 
            WHERE namaKelab = '$clubName'";
        $detailsResult = $conn->query($detailsQuery);

        $details = [];
        if ($detailsResult->num_rows > 0) {
            while ($detail = $detailsResult->fetch_assoc()) {
                $details[] = $detail;
            }
        }

        $topClubs[] = [
            "namaKelab" => $clubName,
            "totalActivities" => $row['totalActivities'],
            "details" => $details,
        ];
    }
}

// Output the data in JSON format
header('Content-Type: application/json');
echo json_encode($topClubs);

$conn->close();
?>
