<?php
$servername = "localhost"; // Or your database server
$username = "root";        // Your database username
$password = "";            // Your database password (empty for default)
$dbname = "user_db";       // The database you're using

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
