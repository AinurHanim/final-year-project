<?php
    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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

    // Get the selected category from the request
    $kategori = $_GET['kategori'];

    // Determine the column to query based on the selected category
    switch($kategori) {
        case 'Sukan dan Permainan':
            $column = '`SUKAN DAN PERMAINAN`';
            break;
        case 'Unit Beruniform':
            $column = '`BADAN BERUNIFORM`';
            break;
        case 'Kelab dan Persatuan':
            $column = '`PERSATUAN (AKADEMIK)`, `KELAB (BUKAN AKADEMIK)`';
            break;
        default:
            $column = '';
    }

    // Query the database to get the relevant kelab data
    if ($column) {
        // Handle cases where multiple columns are queried (e.g., Kelab dan Persatuan)
        if ($kategori === 'Kelab dan Persatuan') {
            $query = "SELECT DISTINCT `PERSATUAN (AKADEMIK)` AS namaKelab FROM datakoko WHERE `PERSATUAN (AKADEMIK)` IS NOT NULL AND `PERSATUAN (AKADEMIK)` != ''
                      UNION
                      SELECT DISTINCT `KELAB (BUKAN AKADEMIK)` AS namaKelab FROM datakoko WHERE `KELAB (BUKAN AKADEMIK)` IS NOT NULL AND `KELAB (BUKAN AKADEMIK)` != ''";
        } else {
            $query = "SELECT DISTINCT $column AS namaKelab FROM datakoko WHERE $column IS NOT NULL AND $column != ''";
        }

        $result = $conn->query($query);

        $kelabOptions = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $kelabOptions[] = $row;
            }
        }

        // Return the options as JSON
        echo json_encode($kelabOptions);
    }

    // Close the connection
    $conn->close();
?>
