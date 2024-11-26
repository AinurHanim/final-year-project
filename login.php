<?php
include 'database_connection.php';
session_start();

// Set the maximum number of failed attempts and lockout duration
$max_attempts = 3; // Lock after 3 failed attempts
$lockout_duration = 4; // Lockout duration in minutes

// Check if the form is submitted
if (isset($_POST['login_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Plaintext password entered by user
    $userType = $_POST['userType']; // Get the user's selected role

    // Query to get the user based on the username
    $stmt = $con->prepare("SELECT id, username, password, nama, userType, failed_attempts, lock_until FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Check if account is locked
        if ($user['lock_until'] && strtotime($user['lock_until']) > time()) {
            $remaining_time = ceil((strtotime($user['lock_until']) - time()) / 60); // Calculate remaining lockout time in minutes
            echo "<script>
                    alert('Akaun anda dikunci kerana terlalu banyak cubaan gagal. Sila cuba lagi selepas $remaining_time minit.');
                    window.location.href = 'login.html';
                  </script>";
            exit;
        }

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Check if the user's role matches the role they are trying to log in as
            if ($user['userType'] === $userType) {
                // Reset failed attempts and unlock account on successful login
                $stmt = $con->prepare("UPDATE register SET failed_attempts = 0, lock_until = NULL WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();

                // Set session variables
                $_SESSION['mylogin'] = true;
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['nama'];
                $_SESSION['userType'] = $user['userType']; // Store the userType

                // Insert into activity_log table
                $stmt = $con->prepare("INSERT INTO activity_log (username, action, timestamp) VALUES (?, 'Login Successful', NOW())");
                $stmt->bind_param("s", $username);
                $stmt->execute();

                // Redirect based on userType
                if ($user['userType'] == 'Guru Pengiring') {
                    header("Location: homepageGp.html");
                } elseif ($user['userType'] == 'Setiausaha') {
                    header("Location: homepageSu.html");
                }
                exit();
            } else {
                // Invalid role
                handleFailedLogin($con, $username);
            }
        } else {
            // Invalid password
            handleFailedLogin($con, $username);
        }
    } else {
        // Invalid username
        handleFailedLogin($con, $username);
    }

    // Close the statement
    $stmt->close();
}

// Function to handle failed login attempts
function handleFailedLogin($con, $username) {
    global $max_attempts, $lockout_duration;

    // Increment failed attempts
    $stmt = $con->prepare("UPDATE register SET failed_attempts = failed_attempts + 1 WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Get the updated failed attempts count
    $stmt = $con->prepare("SELECT failed_attempts FROM register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user['failed_attempts'] >= $max_attempts) {
        // Lock the account
        $lock_until = date("Y-m-d H:i:s", strtotime("+$lockout_duration minutes"));
        $stmt = $con->prepare("UPDATE register SET lock_until = ? WHERE username = ?");
        $stmt->bind_param("ss", $lock_until, $username);
        $stmt->execute();

        echo "<script>
                alert('Akaun anda dikunci kerana terlalu banyak cubaan gagal. Sila cuba lagi selepas $lockout_duration minit.');
                window.location.href = 'login.html';
              </script>";
        exit;
    } else {
        // Notify the user about remaining attempts
        $remaining_attempts = $max_attempts - $user['failed_attempts'];
        echo "<script>
                alert('Cubaan gagal. Anda mempunyai $remaining_attempts cubaan lagi sebelum akaun anda dikunci.');
                window.location.href = 'login.html';
              </script>";
        exit;
    }
}
?>
