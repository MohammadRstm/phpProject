
<?php


// Database credentials
$servername = "localhost";  // Change to your server name or IP address
$username = "root";  // root user 
$password = "";  // no password for root user 
$database = "resourcemanagement";  // our data base 

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>