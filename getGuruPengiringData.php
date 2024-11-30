<?php
session_start();
include 'db_connection.php'; // Ensure this file connects to your database

if (isset($_SESSION['username']) && isset($_SESSION['userType'])) {
    // Get the username from the session
    $username = $_SESSION['username'];
    $userType = $_SESSION['userType'];

    // Ensure that the user is a Guru Pengiring
    if ($userType === 'guru pengiring') {
        // Fetch the data from the register table
        $query = $conn->prepare("SELECT nama, emel, telefon FROM register WHERE username = ? AND userType = ?");
        $query->bind_param('ss', $username, $userType);
        $query->execute();
        $result = $query->get_result();
        
        if ($result->num_rows > 0) {
            // Fetch user data
            $userData = $result->fetch_assoc();
            echo json_encode($userData);
        } else {
            echo json_encode(['error' => 'Tiada data ditemui']);
        }
    } else {
        echo json_encode(['error' => 'Akses tanpa kebenaran']);
    }
} else {
    echo json_encode(['error' => 'Pengguna tidak log masuk']);
}
?>