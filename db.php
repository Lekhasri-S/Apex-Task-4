<?php
// db.php
include 'header.php';
$DB_HOST = '127.0.0.1';   
$DB_USER = 'root';
$DB_PASS = 'sara2004';              
$DB_NAME = 'blog';
$DB_PORT = 3306;         

// Create connection (mysqli OO)
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// start session globally
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

