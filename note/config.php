<?php
// Database configuration
$servername = "localhost"; // Usually 'localhost' for local servers
$username = "root"; // Your phpMyAdmin username
$password = ""; // Your phpMyAdmin password
$dbname = "note";   // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
