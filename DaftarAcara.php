<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Start the session to access session variables

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $namaEvent = strtoupper(filter_input(INPUT_POST, 'namaEvent', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $tarikh = filter_input(INPUT_POST, 'tarikh', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tempat = filter_input(INPUT_POST, 'tempat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $peringkat = filter_input(INPUT_POST, 'peringkat', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $kategoriUnit = filter_input(INPUT_POST, 'kategoriUnit', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $namaKelab = filter_input(INPUT_POST, 'namaKelab', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $namaKelabText = filter_input(INPUT_POST, 'namaKelabText', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Use namaKelabText if kategoriUnit is 'Lain-lain'
if ($kategoriUnit === 'LAIN-LAIN' && !empty($namaKelabText)) {
    $namaKelab = $namaKelabText; // Assign the custom name
} elseif ($kategoriUnit === 'LAIN-LAIN' && empty($namaKelabText)) {
    // If 'Lain-lain' is selected but no name is provided, set an error
    echo "Sila isi nama kelab untuk kategori 'Lain-lain'";
    exit();
}

    // Check if required fields are filled
// Check if required fields are filled
if (empty($namaEvent) || empty($tarikh) || empty($tempat) || empty($peringkat) || empty($kategoriUnit) || (empty($namaKelab && $kategoriUnit === 'LAIN-LAIN'))) {
    echo "Sila isi semua ruangan";
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
echo "Nama Event: $namaEvent\n";
echo "Tarikh: $tarikh\n";
echo "Tempat: $tempat\n";
echo "Peringkat: $peringkat\n";
echo "Kategori Unit: $kategoriUnit\n";
echo "Nama Kelab: $namaKelab\n"; // This should show the correct value
echo "Nama Kelab Text: $namaKelabText\n"; // This should show the custom input
echo "Register By: $registerBy\n";
echo "</pre>";
?>
