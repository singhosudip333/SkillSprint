<?php 
// logout.php
// This script handles user logout by destroying the session and redirecting to the login page.

session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: login.php"); // Redirect to the login page
exit(); // Ensure no further code is executed after the redirect
?>