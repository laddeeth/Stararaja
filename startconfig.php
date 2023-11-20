<?php
/**
 * Set the error reporting.
 *
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors 
ini_set('output_buffering', 0);   // Do not buffer outputs, write directlygit 

/**
 * Start the session.
 *
 */
session_name("kucastara");
session_start();

if(isset($_SESSION['user'])){
	header("Location: profile.php");
	exit;
}

$pagetitle = "Stararaja - ";

/**
 *
 * DB 
 *
 */

$database['dsn']            = 'mysql:host=localhost;dbname=stararaja;';
$database['username']       = 'root';
$database['password']       = 'root';
$database['driver_options'] = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

/**
*
* Check for language
*
**/

$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : "eng";

include 'src/func.php';

?>