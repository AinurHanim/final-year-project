<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "project";  // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    
    // Fetch names from the datapelajar table based on user input
    $sql = "SELECT NAMA FROM datapelajar WHERE NAMA LIKE '%$query%'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<div class='suggestion-item'>" . $row['NAMA'] . "</div>";
        }
    } else {
        echo "<div class='suggestion-item'>No results found</div>";
    }
}

$conn->close();
?>
