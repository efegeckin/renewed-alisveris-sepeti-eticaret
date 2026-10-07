<?php
$hostname = "localhost";
$username = "root";
$password = "mysql";
$database = "deneme";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
    
}
?>