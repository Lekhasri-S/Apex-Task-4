<?php
// db.php

// Start session globally (before any output)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database config
$DB_HOST = '127.0.0.1';   
$DB_USER = 'root';
$DB_PASS = 'sara2004';              
$DB_NAME = 'blog';
$DB_PORT = 3306;         

// Create connection securely
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Set charset to avoid SQL injection via encoding tricks
$conn->set_charset("utf8mb4");
?>
