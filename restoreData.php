<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB connection parameters
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "project";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if (isset($_POST['action']) && $_POST['action'] == 'restore') {
    $response = []; // Array to hold the response data

    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Get the uploaded file's temporary path
        $fileTmpPath = $_FILES['file']['tmp_name'];

        // Open the file for reading
        if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row
            $stmt = $conn->prepare("INSERT INTO dataguru (`Nama Guru`) VALUES (?)");
        
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $namaGuru = $data[0];
        
                $stmt->bind_param("s", $namaGuru);
                if (!$stmt->execute()) {
                    $response['errors'][] = "Error inserting $namaGuru: " . $stmt->error;
                }
            }
        
            fclose($handle);
        }
         else {
            $response['error'] = "Error opening the uploaded file.";
        }
    } else {
        $response['error'] = "No file uploaded or there was an error with the file.";
    }

    // After restoring data, fetch all records from the dataguru table
    $result = $conn->query("SELECT `Nama Guru` FROM dataguru");

    if ($result->num_rows > 0) {
        $response['data'] = [];
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    } else {
        $response['data'] = [];
    }

    if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
        die(json_encode(['error' => 'Muat naik fail gagal.']));
    }
    
    if ($_FILES['file']['type'] != 'text/csv') {
        die(json_encode(['error' => 'Jenis fail tidak sah. Sila muat naik fail CSV.']));
    }
    
    // Before processing the file
  //  die(json_encode(['debug' => 'File uploaded successfully.']));
    

    // Return the response as JSON
    echo json_encode($response);
}
?>
