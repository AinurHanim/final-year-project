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


// Get query parameter from URL
$query = $_GET['query'];

// Query to select student names from datapelajar table that match the query
$sql = "SELECT NAMA FROM datapelajar WHERE NAMA LIKE '%$query%'";

// Execute query
$result = mysqli_query($conn, $sql);

// Check if query was successful
if ($result) {
  // Fetch all rows from result
  $rows = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  // Output rows as JSON
  header('Content-Type: application/json');
  echo json_encode($rows);
} else {
  // Output error message if query failed
  echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>
