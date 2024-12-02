<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB connection
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

// Check if form has been submitted
if (isset($_POST['action']) && $_POST['action'] == 'restoreDataPelajar') {
    // Check if file has been uploaded
    if (isset($_FILES['file-pelajar']) && $_FILES['file-pelajar']['error'] == 0) {
        $fileTmpPath = $_FILES['file-pelajar']['tmp_name'];

        // Check if file type is CSV
        if ($_FILES['file-pelajar']['type'] == 'text/csv') {
            // Open the file for reading
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                fgetcsv($handle); // Skip header row
                $stmt = $conn->prepare("INSERT INTO datapelajar (`NAMA`, `NO K/P`, `TING`) VALUES (?,?,?)");

                // Read the file line by line and insert data into the database
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $namaPelajar = $data[0]; // First column for NAMA
                    $noIc = $data[1]; // Second column for NO K/P
                    $ting = $data[2]; // Third column for TING
                    
                    // Bind parameters and execute the query
                    $stmt->bind_param("sss", $namaPelajar, $noIc, $ting);
                    if (!$stmt->execute()) {
                        // Error message for insertion failure
                        echo "Ralat memasukkan rekod:" . $stmt->error;
                        exit;  // Stop further execution if error occurs
                    }
                }

                fclose($handle); // Close the file after reading
                // Success message
                echo "<script> 
                        alert('Data pelajar berjaya dikemaskini');
                        window.location.href = 'restore.html';
                        </script>"; 
                exit;
            } else {
                // Error message for file opening failure
                echo "<script> 
                alert('Ralat membuka fail yang dimuat naik');
                window.location.href = 'restore.html';
                </script>"; 
        exit;
            }
        } else {
            // Error message for invalid file type
            echo "<script> 
                        alert('Jenis fail tidak sah. Sila muat naik fail CSV.');
                        window.location.href = 'restore.html';
                        </script>"; 
                exit;
        }
    } else {
        // Error message if no file was uploaded
        echo "<script> 
                        alert('Tiada fail dimuat naik atau terdapat ralat dengan fail.');
                        window.location.href = 'restore.html';
                        </script>"; 
                exit;
    }
}
?>
