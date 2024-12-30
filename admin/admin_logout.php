<?php
// Start the session
session_start();

// Destroy the session to log out the user
session_unset();  // Removes all session variables
session_destroy();  // Destroys the session

// Redirect the user to the login page
header("Location: admin_login.php");
exit();
?>
