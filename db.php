<?php
$servername = "localhost";
$username = "root";     // Default for WAMP
$password = "";         // WAMP has NO password by default
$database = "fitsphere"; // Your actual DB name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
