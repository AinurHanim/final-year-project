<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session

// Display the logout message
echo '<script>alert("The user no longer used/has been logged out."); window.location.href = "login_page.php";</script>';
?>
