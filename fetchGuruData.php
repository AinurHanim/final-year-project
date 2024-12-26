<?php
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

// Get the search term from the request, if any
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query
$sql = "SELECT `Nama Guru`, 
               (SELECT COUNT(*) FROM daftaracara da WHERE da.registerBy = dg.`Nama Guru`) AS registered_count 
        FROM dataguru dg 
        WHERE dg.`Nama Guru` LIKE ?";

// Prepare the statement
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $searchTerm . "%"; // Add wildcards for LIKE search
$stmt->bind_param("s", $searchTerm);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Prepare an array to hold the data
$guruData = [];

// Fetch the data
while ($row = $result->fetch_assoc()) {
    $guruData[] = [
        'nama' => $row['Nama Guru'], // Use the correct column name
        'registered' => $row['registered_count'] > 0 // true if registered, false otherwise
    ];
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($guruData);

// Close the statement and connection
$stmt->close();
$conn->close();
?>