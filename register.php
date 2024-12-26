<?php
// Retrieve and sanitize form data
$nama = strtoupper(htmlspecialchars($_POST['nama'])); // Convert to uppercase
$email = htmlspecialchars($_POST['email']);
$telefon = htmlspecialchars($_POST['telefon']);
$username = htmlspecialchars($_POST['username']);
$password = $_POST['password']; // Get the raw password for validation
$userType = htmlspecialchars($_POST['userType']);

// Validation checks
$errors = [];

// Database connection details
$servername = "localhost";
$dbusername = "root"; // Your database username
$dbpassword = ""; // Your database password
$dbname = "project"; // Your database name

try {
    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if the email exists in the dataguru table
    $stmt_email_check = $conn->prepare("SELECT COUNT(*) FROM dataguru WHERE email = ?");
    $stmt_email_check->bind_param("s", $email);
    $stmt_email_check->execute();
    $stmt_email_check->bind_result($email_count);
    $stmt_email_check->fetch();
    $stmt_email_check->close();

    // If email doesn't exist, show an error
    if ($email_count == 0) {
        echo "<script>
            alert('Email tidak terdaftar. Hanya email yang ada dalam sistem yang boleh digunakan untuk mendaftar.');
            window.history.back();
        </script>";
        exit; // Stop further processing
    }

    // Validation checks for username and password
    if (!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
        echo "<script>
            alert('Nama pengguna mestilah sekurang-kurangnya 3 aksara panjang dan tidak mengandungi aksara khas.');
            window.history.back();
        </script>";
        exit; // Stop further processing
    }

    if (!preg_match('/^(?=.*[!@#$%^&*])(?=.{8,})/', $password)) {
        echo "<script>
            alert('Kata laluan mestilah sekurang-kurangnya 8 aksara panjang dan termasuk sekurang-kurangnya satu aksara khas.');
            window.history.back();
        </script>";
        exit; // Stop further processing
    }

    if (empty($userType)) {
        echo "<script>
            alert('Jenis pengguna mesti dipilih.');
            window.history.back();
        </script>";
        exit; // Stop further processing
    }

    // Check if there is already a PK KOKO registered
    if ($userType === 'PK KOKO') {
        $stmt_pk_check = $conn->prepare("SELECT COUNT(*) FROM register WHERE userType = 'PK KOKO'");
        $stmt_pk_check->execute();
        $stmt_pk_check->bind_result($pk_count);
        $stmt_pk_check->fetch();
        $stmt_pk_check->close();

        if ($pk_count > 0) {
            echo "<script>
                alert('Hanya satu pengguna boleh mendaftar sebagai PK KOKO. Pengguna PK KOKO sudah ada');
                window.history.back();
            </script>";
            exit; // Stop further processing
        }
    }

    // Hash the password after validation
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the name already exists in the dataguru table
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM dataguru WHERE `Nama Guru` = ? AND email = ?");
    $stmt_check->bind_param("ss", $nama, $email);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    // If name and email don't exist, insert it into the dataguru table
    if ($count == 0) {
        $stmt_insert = $conn-> prepare("INSERT INTO dataguru (`Nama Guru`, `email`) VALUES (?, ?)");
        $stmt_insert->bind_param("ss", $nama, $email); // Bind both name and email
        if (!$stmt_insert->execute()) {
            echo "<script>
                alert('Error inserting into dataguru: " . addslashes($stmt_insert->error) . "');
            </script>";
        }
        $stmt_insert->close();
    }

    // Prepare and bind for the register table
    $stmt = $conn->prepare("INSERT INTO register (nama, email, telefon, username, password, userType) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $email, $telefon, $username, $password, $userType);

    // Execute the statement for the register table
    $stmt->execute();

    // Check if user Type is 'Guru Pengiring' to insert into guru_pengiring table
    if ($userType === 'Guru Pengiring') {
        $stmt_guru = $conn->prepare("INSERT INTO guru_pengiring (nama) VALUES (?)");
        $stmt_guru->bind_param("s", $nama);
        $stmt_guru->execute();
        $stmt_guru->close();
    }

    // Display success message
    echo "<script>
        alert('Akaun anda berjaya didaftarkan!');
        window.location.href = 'SchoolWebsite.html';
    </script>";
} catch (mysqli_sql_exception $e) {
    // Handle duplicate email error
    if ($e->getCode() === 1062) { // 1062 is the error code for duplicate entry
        echo "<script>
            alert('Alamat email telah digunakan oleh pengguna lain.');
            window.history.back();
        </script>";
    } else {
        // General error handling
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
        </script>";
    }
} finally {
    // Close the statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}