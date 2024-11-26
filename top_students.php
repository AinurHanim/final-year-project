<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the response content type to JSON
header('Content-Type: application/json');

try {
    // Query to get the top 3 students with the most program participation
    $topStudentsQuery = "SELECT nama_pelajar, no_ic, COUNT(*) AS total_participation
                         FROM updatepenyertaan
                         GROUP BY nama_pelajar, no_ic
                         ORDER BY total_participation DESC
                         LIMIT 3";
    $topStudentsResult = $conn->query($topStudentsQuery);

    // Check if the query was successful
    if (!$topStudentsResult) {
        throw new Exception("Database Query Failed: " . $conn->error);
    }

    // Prepare data for each top student including their event details
    $data = [];
    while ($student = $topStudentsResult->fetch_assoc()) {
        $nama_pelajar = $student['nama_pelajar'];
        $no_ic = $student['no_ic'];

        // Fetch all events participated by each top student
        $eventsQuery = "SELECT kategori_badan_unit, nama_kelab, acara, kelas, pencapaian, peringkatAcara, registerBy
                        FROM updatepenyertaan
                        WHERE nama_pelajar = '$nama_pelajar' AND no_ic = '$no_ic'";
        $eventsResult = $conn->query($eventsQuery);
        
        // Gather event details in an array
        $events = [];
        while ($event = $eventsResult->fetch_assoc()) {
            $events[] = $event;
        }

        // Append student data and events to the main data array
        $data[] = [
            'nama_pelajar' => $nama_pelajar,
            'no_ic' => $no_ic,
            'total_participation' => (int)$student['total_participation'],
            'events' => $events
        ];
    }

    // Output data in JSON format
    echo json_encode($data);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the database connection
$conn->close();
?>
