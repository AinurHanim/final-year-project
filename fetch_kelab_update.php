<?php
header('Content-Type: application/json');

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

// Get the selected category from the URL parameter
$category = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// Validate category
$validCategories = ['SUKAN DAN PERMAINAN', 'PERSATUAN (AKADEMIK)', 'KELAB (BUKAN AKADEMIK)', 'BADAN BERUNIFORM'];
if (!in_array($category, $validCategories)) {
    error_log("Invalid category: $category");
    echo json_encode([]);
    exit;
}

// Prepare array to hold clubs
$clubs = [];

// Fetch clubs from the newdatakoko table based on the selected category
$sql_newdatakoko = "SELECT name FROM newdatakoko WHERE category = ?";
$stmt_newdatakoko = $conn->prepare($sql_newdatakoko);

if ($stmt_newdatakoko) {
    $stmt_newdatakoko->bind_param("s", $category);
    $stmt_newdatakoko->execute();
    $result_newdatakoko = $stmt_newdatakoko->get_result();

    while ($row = $result_newdatakoko->fetch_assoc()) {
        $clubs[] = $row; // Add each club's name to the result
    }
    $stmt_newdatakoko->close();
} else {
    error_log("SQL Error: " . $conn->error);
}



// Close connection
$conn->close();

// Log and return the clubs as a JSON response
if (empty($clubs)) {
    error_log("No clubs found for category: $category");
}
echo json_encode($clubs);
?>
