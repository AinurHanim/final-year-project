<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

if (empty($_POST['email']) || empty($_POST['password'])) {
    echo json_encode(["status" => "error", "message" => "Medan e-mel atau kata laluan tidak boleh kosong"]);
    exit;
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

$check_stmt = $conn->prepare("SELECT * FROM register WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $update_stmt = $conn->prepare("UPDATE register SET password = ? WHERE email = ?");
    $update_stmt->bind_param("ss", $passwordHash, $email);

    if ($update_stmt->execute()) {
        if ($update_stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Kata laluan berjaya dikemaskini."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Tiada perubahan dibuat atau e-mel tidak dijumpai."]);
        }
    } else {
        error_log("SQL Error: " . $conn->error);
        echo json_encode(["status" => "error", "message" => "SQL Error occurred. Sila hubungi pentadbir."]);
    }
    $update_stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "E-mel tidak ditemui dalam pangkalan data."]);
}

$check_stmt->close();
$conn->close();
?>
