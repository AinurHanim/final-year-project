<?php
$host = 'localhost';
$db = 'project';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete data from the tables
$tables = ['dataguru', 'datapelajar', 'updatepenyertaan', 'daftarunitbadan', 'daftaracara' ];
foreach ($tables as $table) {
    $conn->query("DELETE FROM $table");
}

$conn->close();

// Redirect back to homepage or confirmation page
header('Location: homepageSu.html');
exit();
?>
