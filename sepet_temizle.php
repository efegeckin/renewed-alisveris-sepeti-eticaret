<!-- veritabanına bağlan sepet tablosunun içeriğini temizle -->
<?php
include 'admin/baglanti.php';
checkAuth();

$kullanici_id = (int) $_SESSION['kullanici_id'];
mysqli_query($conn, "DELETE FROM sepet WHERE kullanici_id = $kullanici_id");
header('Location: sepet.php');
exit;
