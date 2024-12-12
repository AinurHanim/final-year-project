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

if (!preg_match('/^[a-zA-Z0-9]{3,}$/', $username)) {
    $errors[] = 'Username must be at least 3 characters long and contain no special characters.';
}

if (!preg_match('/^(?=.*[!@#$%^&*])(?=.{8,})/', $password)) {
    $errors[] = 'Password must be at least 8 characters long and include at least one special character.';
}

if (empty($userType)) {
    $errors[] = 'User type must be selected.';
}

// If there are errors, handle them
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='error'>$error</div>";
    }
    exit; // Stop further processing
}

// Hash the password after validation
$password = password_hash($password, PASSWORD_DEFAULT);

// Database connection parameters
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

    // Check if the name already exists in the dataguru table
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM dataguru WHERE `Nama Guru` = ?");
    $stmt_check->bind_param("s", $nama);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    // If name doesn't exist, insert it into the dataguru table
    if ($count == 0) {
        $stmt_insert = $conn->prepare("INSERT INTO dataguru (`Nama Guru`) VALUES (?)");
        $stmt_insert->bind_param("s", $nama);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    // Prepare and bind for the register table
    $stmt = $conn->prepare("INSERT INTO register (nama, email, telefon, username, password, userType) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $email, $telefon, $username, $password, $userType);

    // Execute the statement for the register table
    $stmt->execute();

    // Check if userType is 'Guru Pengiring' to insert into guru_pengiring table
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
?>
