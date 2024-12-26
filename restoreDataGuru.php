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
    if (isset($_FILES['file-guru']) && $_FILES['file-guru']['error'] == 0) {
        // Get the uploaded file's temporary path
        $fileTmpPath = $_FILES['file-guru']['tmp_name'];

        // Check if file type is csv
        if ($_FILES['file-guru']['type'] == 'text/csv') {  //File Upload Validation -- security features (only csv file are allowed)
            if (($handle = fopen($fileTmpPath, "r")) !== FALSE) {
                fgetcsv($handle);  // Skip the header row
                $stmt = $conn->prepare("INSERT INTO dataguru (`Nama Guru`) VALUES (?)");

                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $namaGuru = $data[0];

                    // Correct bind_param method
                    $stmt->bind_param("s", $namaGuru);

                    try {
                        $stmt->execute();
                    } catch (mysqli_sql_exception $e) {
                        // Check if the error is a duplicate entry
                        if ($e->getCode() == 1062) { // MySQL error code for duplicate entry
                            echo "<script> 
                                    alert('Data yang diisi sudah ada dalam rekod. Tidak boleh upload secara duplikat.');
                                    window.location.href = 'restore.html';
                                  </script>";
                            exit;
                        } else {
                            echo "<script> 
                                    alert('Ralat lain berlaku: {$e->getMessage()}');
                                    window.location.href = 'restore.html';
                                  </script>";
                            exit;
                        }
                    }
                }

                fclose($handle); // Close file after reading
                echo "<script> 
                        alert('Data guru berjaya dikemaskini');
                        window.location.href = 'restore.html';
                      </script>";
                exit;
            } else {
                echo "<script> 
                        alert('Ralat membuka fail yang dimuat naik.');
                        window.location.href = 'restore.html';
                      </script>";
                exit;
            }
        } else {
            echo "<script> 
                    alert('Jenis fail tidak sah. Sila muat naik fail CSV.');
                    window.location.href = 'restore.html';
                  </script>";
            exit;
        }
    } else {
        echo "<script> 
                alert('Tiada fail dimuat naik atau terdapat ralat dengan fail.');
                window.location.href = 'restore.html';
              </script>";
        exit;
    }
}

?>
