<?php
$host = 'localhost';
$user = 'flaviovacca2025';
$pass = '';
$dbname = 'my_flaviovacca2025';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>