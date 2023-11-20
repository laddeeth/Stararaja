<?php
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directly

/**
 * Start the session.
 *
 */
session_name("kucastara");
session_start();

session_unset();

session_destroy();


header("Location: index.php");
exit;