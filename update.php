<head>
  <script src="jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>
<?php
session_start();

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

// Check if the user is logged in
if (!isset($_SESSION['full_name'])) {
    die("User not logged in.");
}

// Sanitize and validate form data
$kategoriBadan = filter_input(INPUT_POST, 'kategoriBadan', FILTER_SANITIZE_STRING);
$namaKelab = filter_input(INPUT_POST, 'namaKelab', FILTER_SANITIZE_STRING) ?: filter_input(INPUT_POST, 'namaKelabText', FILTER_SANITIZE_STRING);
$events = $_POST['event'] ?? [];
$namaPelajar = $_POST['namaPelajarText'] ?? [];
$noIc = $_POST['noIc'] ?? [];
$kelas = $_POST['kelas'] ?? [];
$pencapaian = $_POST['pencapaian'] ?? [];

// Validation: Check if required fields are empty
if (empty($kategoriBadan) || empty($namaKelab) || empty($events) || empty($namaPelajar) || empty($noIc) || empty($kelas) || empty($pencapaian)) {
    echo "<script>alert('Sila isi data terlebih dahulu'); window.history.back();</script>";
    exit();
}

if (count($noIc) !== count($namaPelajar)) {
    echo "<script>alert('Mismatch between the number of students and ICs. Sila semak semula.'); window.history.back();</script>";
    exit();
}

// Prepare SQL query for updating the database
$stmt = $conn->prepare("INSERT INTO updatepenyertaan (kategori_badan_unit, nama_kelab, acara, nama_pelajar, no_ic, kelas, pencapaian, peringkatAcara, registerBy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Loop through form data and store in database
for ($i = 0; $i < count($namaPelajar); $i++) {
    $stmt->bind_param(
        "sssssssss",
        $kategoriBadan,
        $namaKelab,
        $events[0],
        $namaPelajar[$i],
        $noIc[$i],
        $kelas[$i],
        $pencapaian[$i],
        $_POST['peringkatAcara'],
        $_SESSION['full_name']
    );
    
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
}

$stmt->close();
$conn->close();

// Display success alert and redirect to homepage
echo "<script>alert('Data penyertaan berjaya disimpan'); window.location.href = 'homepageGp.html';</script>";
exit();
?>
