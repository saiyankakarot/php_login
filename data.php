<?php
// Database configuration
$host = 'localhost';        // XAMPP default host
$user = 'root';             // XAMPP default MySQL username
$password = '';             // Default password is empty in XAMPP
$database = 'user_auth_demo'; // Your database name

// Create connection
$con = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// echo "Connected successfully!";
?>
