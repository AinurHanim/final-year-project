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

$category = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

$validCategories = ['SUKAN DAN PERMAINAN', 'PERSATUAN (AKADEMIK)', 'KELAB (BUKAN AKADEMIK)', 'BADAN BERUNIFORM'];
if (!in_array($category, $validCategories)) {
    error_log("Invalid category: $category");
    echo json_encode([]);
    exit;
}

$clubs = [];
$sql_newdatakoko = "SELECT name FROM newdatakoko WHERE category = ?";
$stmt_newdatakoko = $conn->prepare($sql_newdatakoko);

if ($stmt_newdatakoko) {
    $stmt_newdatakoko->bind_param("s", $category);
    $stmt_newdatakoko->execute();
    $result_newdatakoko = $stmt_newdatakoko->get_result();

    while ($row = $result_newdatakoko->fetch_assoc()) {
        $clubs[] = $row;
    }
    $stmt_newdatakoko->close();
} else {
    error_log("SQL Error: " . $conn->error);
}

$conn->close();
echo json_encode($clubs);

?>
