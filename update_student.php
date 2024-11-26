<?php
session_start();

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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the expected fields are set and not empty
    if (!isset($_POST['namaPelajarText'], $_POST['noIc'], $_POST['pencapaian']) ||
        empty($_POST['namaPelajarText']) || 
        empty($_POST['noIc']) || 
        empty($_POST['pencapaian'])) {
        
        echo json_encode(array('success' => false, 'error' => 'Sila isi data semula'));
        exit; // Stop further execution
    }

    $namaPelajarArray = $_POST['namaPelajarText'];
    $noIcArray = $_POST['noIc'];
    $pencapaianArray = $_POST['pencapaian'];

    // Prepare the SQL update statement
    $updateQuery = "UPDATE updatepenyertaan SET pencapaian = ? WHERE no_ic = ? AND nama_pelajar = ?";

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Loop through the students
        for ($i = 0; $i < count($namaPelajarArray); $i++) {
            $namaPelajar = $namaPelajarArray[$i];
            $noIc = $noIcArray[$i];
            $pencapaian = $pencapaianArray[$i];

            // Update the student's achievement
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("sss", $pencapaian, $noIc, $namaPelajar);
            $updateStmt->execute();
            $updateStmt->close();
        }

        // Commit the transaction
        $conn->commit();

        // Return a success message
        echo json_encode(array('success' => true, 'message' => 'Data pelajar berjaya dikemaskini!'));
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();

        // Return an error message
        echo json_encode(array('success' => false, 'error' => $e->getMessage()));
    }
} else {
    // Return an error message if the request method is not POST
    echo json_encode(array('success' => false, 'error' => 'Invalid request method'));
}

// Close the database connection
$conn->close();
?>