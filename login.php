<?php
include 'database_connection.php';
session_start();

// Set the maximum number of failed attempts and lockout duration
$max_attempts = 3; // Lock after 3 failed attempts
$lockout_duration = 4; // Lockout duration in minutes

if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to get user data
    $stmt = $con->prepare("SELECT id, username, password, nama, userType, failed_attempts, lock_until FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if account is locked
        if ($user['lock_until'] && strtotime($user['lock_until']) > time()) {
            $remaining_time = ceil((strtotime($user['lock_until']) - time()) / 60);
            echo "<script>
                    alert('Akaun anda dikunci kerana terlalu banyak cubaan gagal. Sila cuba lagi selepas $remaining_time minit.');
                    window.location.href = 'SchoolWebsite.html';
                  </script>";
            exit;
        }

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Reset failed attempts and unlock account
            $stmt = $con->prepare("UPDATE register SET failed_attempts = 0, lock_until = NULL WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();

            // Set session variables
            $_SESSION['mylogin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['nama'];
            $_SESSION['userType'] = $user['userType'];

            // Log the successful login
            $stmt = $con->prepare("INSERT INTO activity_log (username, action, timestamp) VALUES (?, 'Login Successful', NOW())");
            $stmt->bind_param("s", $username);
            $stmt->execute();

            // Redirect based on user role
            if ($user['userType'] === 'Guru Pengiring') {
                header("Location: homepageGp.html");
            } elseif ($user['userType'] === 'Setiausaha') {
                header("Location: homepageSu.html");
            } else {
                echo "<script>alert('Jenis pengguna tidak dikenali. Sila hubungi pentadbir.');</script>";
                exit;
            }
        } else {
            handleFailedLogin($con, $username);
        }
    } else {
        echo "<script>
                alert('Akaun anda tiada dalam rekod. \\n Sila daftar terlebih dahulu.');
                window.location.href = 'SchoolWebsite.html';
              </script>";
        exit;
    }
    $stmt->close();
}

function handleFailedLogin($con, $username) {
    global $max_attempts, $lockout_duration;

    // Increment failed attempts
    $stmt = $con->prepare("UPDATE register SET failed_attempts = failed_attempts + 1 WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Get updated failed attempts
    $stmt = $con->prepare("SELECT failed_attempts FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['failed_attempts'] >= $max_attempts) {
        $lock_until = date("Y-m-d H:i:s", strtotime("+$lockout_duration minutes"));
        $stmt = $con->prepare("UPDATE register SET lock_until = ? WHERE username = ?");
        $stmt->bind_param("ss", $lock_until, $username);
        $stmt->execute();

        echo "<script>
                alert('Akaun anda dikunci kerana terlalu banyak cubaan gagal. Sila cuba lagi selepas $lockout_duration minit.');
                window.location.href = 'SchoolWebsite.html';
              </script>";
        exit;
    } else {
        $remaining_attempts = $max_attempts - $user['failed_attempts'];
        echo "<script>
                alert('Cubaan gagal. Anda mempunyai $remaining_attempts cubaan lagi sebelum akaun anda dikunci.');
                window.location.href = 'SchoolWebsite.html';
              </script>";
        exit;
    }
}
?>
