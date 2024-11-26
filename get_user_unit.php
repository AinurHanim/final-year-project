<?php
session_start();
include 'db_connection.php'; // Masukkan fail sambungan database anda

// Semak jika pengguna telah log masuk
if (isset($_SESSION['username']) && isset($_SESSION['userType'])) {
    $username = $_SESSION['username'];
    $userType = $_SESSION['userType'];

    // Dapatkan kategoriUnit dan namaKelab dari table register/daftarunitbadan
    $query = "SELECT daftarunitbadan.typeUnit AS kategoriUnit, daftarunitbadan.namaUnit AS namaKelab 
              FROM register 
              INNER JOIN daftarunitbadan 
              ON register.nama = daftarunitbadan.nama 
              WHERE register.username = '$username' AND register.userType = '$userType'";
              
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Return data dalam format JSON
        echo json_encode(array(
            'kategoriUnit' => $row['kategoriUnit'],
            'namaKelab' => $row['namaKelab']
        ));
    } else {
        // Jika tiada data dijumpai
        echo json_encode(array('error' => 'Tiada data dijumpai untuk pengguna ini.'));
    }
} else {
    // Pengguna tidak log masuk
    echo json_encode(array('error' => 'Pengguna tidak log masuk.'));
}
?>
