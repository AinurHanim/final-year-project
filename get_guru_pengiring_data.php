<?php
// Include your database connection
session_start();

if (isset($_GET['fullName'])) {
    $fullName = $_GET['fullName'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "project");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT typeUnit, namaUnit FROM daftarunitbadan WHERE guruPenasihat = ?");
    $stmt->bind_param("s", $fullName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(null); // No data found
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(null); // No fullName in GET request
}

?>
