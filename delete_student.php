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

$data = json_decode(file_get_contents("php://input"), true);
file_put_contents('php://stderr', print_r($data, TRUE)); // Log the data received

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($data['no_ic']) || empty($data['no_ic']) || !isset($data['nama_pelajar']) || empty($data['nama_pelajar'])) {
        echo json_encode(array('success' => false, 'error' => 'No IC number or name provided.'));
        exit; // Stop further execution
    }

    $noIc = $data['no_ic'];
    $namaPelajar = $data['nama_pelajar'];

    // Prepare the SQL delete statement
    $deleteQuery = "DELETE FROM updatepenyertaan WHERE no_ic = ? AND nama_pelajar = ?";

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare and execute the delete statement
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ss", $noIc, $namaPelajar);
        $deleteStmt->execute();

        // Check if any rows were affected
        if ($deleteStmt->affected_rows > 0) {
            // Commit the transaction
            $conn->commit();
            echo json_encode(array('success' => true, 'message' => 'Pelajar berjaya dibuang.'));
        } else {
            // Rollback if no rows were affected
            $conn->rollback();
            echo json_encode(array('success' => false, 'error' => 'No student found with the provided IC number and name.'));
        }

        $deleteStmt->close();
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        echo json_encode(array('success' => false, 'error' => 'Error: ' . $e->getMessage()));
    }
} else {
    // Return an error message if the request method is not DELETE
    echo json_encode(array('success' => false, 'error' => 'Invalid request method'));
}

// Close the database connection
$conn->close();
?>