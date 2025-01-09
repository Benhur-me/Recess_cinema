<?php
session_start();  // Start session to manage login

// Destroy the session to log the user out
session_unset();
session_destroy();

// Redirect to login page after logout
header('Location: login.php');
exit();
?>
