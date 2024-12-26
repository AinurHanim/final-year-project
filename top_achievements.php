<?php
// Database connection
$servername = "localhost";
$username = "root"; // replace with your DB username
$password = ""; // replace with your DB password
$dbname = "project"; // database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch top 3 events with the highest level, excluding participation count
$sql = "SELECT namaEvent, peringkat, tarikh, tempat, kategoriUnit, namaKelab, registerBy 
        FROM daftaracara 
        ORDER BY peringkat DESC, tarikh ASC 
        LIMIT 3";

$result = $conn->query($sql);

$topAchievements = array();
while ($row = $result->fetch_assoc()) {
    $eventDetails = array(
        'namaEvent' => $row['namaEvent'],
        'peringkat' => $row['peringkat'],
        'tarikh' => $row['tarikh'],
        'tempat' => $row['tempat'],
        'kategoriUnit' => $row['kategoriUnit'],
        'namaKelab' => $row['namaKelab'],
        'registerBy' => $row['registerBy'],
        'details' => array() // additional details if needed
    );

    // You can add more logic here to fetch other related data if needed

    $topAchievements[] = $eventDetails;
}

$conn->close();

// Return data as JSON
echo json_encode($topAchievements);
?>
