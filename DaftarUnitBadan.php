<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if 'typeUnit' is set in POST data
    if (!isset($_POST['typeUnit'])) {
        die("Error: typeUnit is not set.");
    }

    // Retrieve and sanitize form data
    $typeUnit = $_POST['typeUnit'];
    $namaUnit = isset($_POST['namaUnit']) ? $_POST['namaUnit'] : '';
    if ($namaUnit === '' && isset($_POST['namaUnitText'])) {
        $namaUnit = $_POST['namaUnitText'];
    }

    $namaArray = isset($_POST['guruPengiring']) ? $_POST['guruPengiring'] : []; // This will now be an array

    // Check if $namaArray is not empty
    if (empty($namaArray)) {
        die("Error: No Guru Pengiring selected.");
    }

    // Concatenate the names into a single string
    $nama = implode(', ', $namaArray);

    // Database connection settings
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

    // Start transaction to ensure both tables are updated atomically
    $conn->begin_transaction();

    try {
        // Insert data into daftarunitbadan table
        foreach ($namaArray as $namaSingle) {
            $sql = "INSERT INTO daftarunitbadan (typeUnit, namaUnit, nama) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $typeUnit, $namaUnit, $namaSingle);

            if (!$stmt->execute()) {
                throw new Exception("Error inserting into daftarunitbadan: " . $stmt->error);
            }
        }

        // Update kategoriUnit, namaKelab, and nama in the register table where the nama matches
        foreach ($namaArray as $namaSingle) {
            $update_sql = "UPDATE register 
                           SET kategoriUnit = ?, namaKelab = ? 
                           WHERE TRIM(nama) = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sss", $typeUnit, $namaUnit, $namaSingle);
            if (!$update_stmt->execute()) {
                throw new Exception("Error updating register: " . $update_stmt->error);
            }
        }

        // Commit the transaction
        $conn->commit();

        // Success message and redirection
        echo "<script>
            alert('Maklumat guru telah didaftarkan');
            window.location.href = 'HomepageSu.html';
        </script>";
        exit();

    } catch (Exception $e) {
        // If any error occurs, rollback the transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close the statements and the database connection
    $stmt->close();
    if (isset($update_stmt)) {
        $update_stmt->close(); // Ensure $update_stmt exists before closing
    }
    $conn->close();

} else {
    echo "Invalid request method.";
}
?>