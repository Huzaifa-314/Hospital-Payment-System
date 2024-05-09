<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login.php with logout message
header("Location: login.php?success=You+have+been+logged+out.");
exit;
?>
