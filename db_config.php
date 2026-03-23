<?php

$db_host = "localhost:3306";
$db_user = "root";           
$db_password = "Kslhefuwi-1";           
$db_name = "tinder_db";

// Kreiraj konekciju
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Proveri konekciju
if ($conn->connect_error) {
    die("Greška pri konekciji na bazu: " . $conn->connect_error);
}

// Postavi charset
$conn->set_charset("utf8mb4");

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

