<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'secure_crud_auth';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to prevent SQL injection
$conn->set_charset("utf8mb4");
?>