<?php
$hostname = "localhost";
$username = "root";
$password = '';
$database = "alisveris";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Bağlantı hatası: " . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8mb4');


//giren kullanıcın rol bilgisini al ve kontrol et

function checkAdmin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['kullanici_id'], $_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
        header("Location: ../login.php");
        exit;
    }
    return true;
}

function checkAuth()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['kullanici_id'])) {
        header("Location: login.php");
        exit;
    }
    return true;
}
