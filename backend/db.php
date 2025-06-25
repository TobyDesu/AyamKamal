<?php
// db.php: Handles the connection to the Oracle XE database.

$host = "localhost:1521/FREEPDB1"; // Replace with your Oracle service name
$username = "ayam_pro"; // Oracle username
$password = "ayam123"; // Oracle password

// Establish connection
$conn = oci_connect($username, $password, $host);

if (!$conn) {
    $e = oci_error();
    die("Connection failed: " . $e['message']);
}
?>