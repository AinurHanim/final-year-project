<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Tables to export
$tables = ['datapelajar', 'dataguru', 'updatepenyertaan'];

// Temporary directory for CSV files
$csvDir = 'backup_csv/';
if (!is_dir($csvDir)) {
    mkdir($csvDir, 0777, true);
}

// Create CSV files
foreach ($tables as $table) {
    $filePath = $csvDir . $table . '.csv';
    $output = fopen($filePath, 'w');

    // Write column headers
    $columnsQuery = "SHOW COLUMNS FROM $table";
    $columnsResult = mysqli_query($con, $columnsQuery); // Changed $conn to $con

    if ($columnsResult) {
        $columns = [];
        while ($row = mysqli_fetch_assoc($columnsResult)) {
            $columns[] = $row['Field'];
        }
        fputcsv($output, $columns);
    }

    // Write table data
    $dataQuery = "SELECT * FROM $table";
    $dataResult = mysqli_query($con, $dataQuery); // Changed $conn to $con

    if ($dataResult) {
        while ($row = mysqli_fetch_assoc($dataResult)) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
}

// Create a ZIP file with the CSV files
$zipFile = 'backup_' . date('Ymd_His') . '.zip';
$zip = new ZipArchive();

if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
    foreach ($tables as $table) {
        $filePath = $csvDir . $table . '.csv';
        if (file_exists($filePath)) {
            $zip->addFile($filePath, basename($filePath));
        }
    }
    $zip->close();

    // Send ZIP file to browser
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFile . '"');
    header('Content-Length: ' . filesize($zipFile));
    readfile($zipFile);

    // Clean up
    unlink($zipFile);
    foreach ($tables as $table) {
        unlink($csvDir . $table . '.csv');
    }
    rmdir($csvDir);
} else {
    echo "Failed to create ZIP file.";
}

mysqli_close($con); // Changed $conn to $con
?>
