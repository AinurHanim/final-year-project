<?php
$query = $_GET['query'];

// Connect to the database
$conn = new mysqli("localhost", "root", "", "project");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
if (isset($query)) {
    $stmt = $conn->prepare("SELECT dg.`Nama Guru`, dub.`typeUnit`, dub.`namaUnit` 
                            FROM dataguru dg 
                            LEFT JOIN daftarunitbadan dub ON dg.`Nama Guru` = dub.`nama` 
                            WHERE LOWER(dg.`Nama Guru`) LIKE ?");
    $param = '%' . strtolower($query) . '%';
    $stmt->bind_param("s", $param);
} else {
    $stmt = $conn->prepare("SELECT dg.`Nama Guru`, dub.`typeUnit`, dub.`namaUnit` 
                            FROM dataguru dg 
                            LEFT JOIN daftarunitbadan dub ON dg.`Nama Guru` = dub.`nama`");
}

// Execute the query
if ($stmt->execute()) {
    // Get the result
    $result = $stmt->get_result();

    // Fetch the data
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Close the statement
    $stmt->close();

    // Return the data in JSON format
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    // Handle any errors that might occur
    http_response_code(500);
    echo json_encode(array('error' => 'Error fetching data'));
}

// Close the database connection
$conn->close();
?>