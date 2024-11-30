<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session to access session variables

// Check if the form was submitted
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $namaEvent = filter_input(INPUT_POST, 'namaEvent', FILTER_SANITIZE_STRING);
    $tarikh = filter_input(INPUT_POST, 'tarikh', FILTER_SANITIZE_STRING);
    $tempat = filter_input(INPUT_POST, 'tempat', FILTER_SANITIZE_STRING);
    $peringkat = filter_input(INPUT_POST, 'peringkat', FILTER_SANITIZE_STRING);
    $kategoriUnit = filter_input(INPUT_POST, 'kategoriUnit', FILTER_SANITIZE_STRING);
    $namaKelab = filter_input(INPUT_POST, 'namaKelab', FILTER_SANITIZE_STRING);
    $namaKelabText = filter_input(INPUT_POST, 'namaKelabText', FILTER_SANITIZE_STRING);

    // Use namaKelabText if namaKelab is empty (for "Lain-lain" case)
    if ($kategoriUnit === 'Lain-lain' && !empty($namaKelabText)) {
        $namaKelab = $namaKelabText;
    }

    // Check if required fields are filled
    if (empty($namaEvent) || empty($tarikh) || empty($tempat) || empty($peringkat) || empty($kategoriUnit) || empty($namaKelab)) {
        echo "Sila isi semua ruangan";
        exit();
    }

    // Use namaKelabText if namaKelab is empty (for "Lain-lain" case)
    if ($kategoriUnit === 'Lain-lain' && !empty($namaKelabText)) {
        $namaKelab = $namaKelabText; // Use the new input value
    }

    // Check if namaKelab is still empty
    if (empty($namaKelab)) {
        echo "Sila berikan nama untuk kelab.";
        exit();
    }

    // Retrieve the user's full name from the session
    $registerBy = $_SESSION['full_name'] ?? null;

    if (empty($registerBy)) {
        echo "Ralat: Pengguna tidak log masuk atau sesi tamat tempoh.";
        exit();
    }

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

    // Prepare and bind to daftaracara table with registerBy column
    $stmt = $conn->prepare("INSERT INTO daftaracara (namaEvent, tarikh, tempat, peringkat, registerBy, kategoriUnit, namaKelab) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "Error preparing statement: " . $conn->error;
        exit();
    }

    // Bind the parameters
    $stmt->bind_param("sssssss", $namaEvent, $tarikh, $tempat, $peringkat, $registerBy, $kategoriUnit, $namaKelab);

    // Execute the statement
    if ($stmt->execute()) {
 echo "<script>
 alert('Acara telah didaftarkan oleh $registerBy');
            window.location.href = 'HomepageGp.html';
        </script>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Kaedah permintaan tidak sah.";
}

echo "<pre>";
print_r($_POST); // Print all POST data
echo "</pre>";
?>
