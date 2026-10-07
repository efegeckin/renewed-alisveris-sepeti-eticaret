<?php
include 'baglanti.php';
checkAdmin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (int)($_POST['id'] ?? 0) < 1) {
    header('Location: telefon.php');
    exit;
}
$id = (int) $_POST['id'];
$stmt = mysqli_prepare($conn, 'DELETE FROM telefon WHERE id = ?');
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
header('Location: telefon.php?deleted=1');
exit;
