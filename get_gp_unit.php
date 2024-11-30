<?php
session_start();

// Assuming you have a database connection established
$conn = mysqli_connect("localhost", "root", "", "project");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the user's full name from the session
$fullName = $_SESSION['full_name'];

// Query the database to get the typeUnit and namaUnit options
$stmt = $conn->prepare("SELECT typeUnit, namaUnit FROM daftarunitbadan WHERE guruPenasihat = ?");
$stmt->bind_param("s", $fullName);
$stmt->execute();
$result = $stmt->get_result();


if (!isset($_SESSION['full_name'])) {
    // Redirect the user to the login page or handle the error
    echo json_encode(array('error' => 'Pengguna tidak log masuk'));
    exit;
}

error_log('Full name from session: ' . $_SESSION['full_name']);


 mysqli_close($conn);
?>