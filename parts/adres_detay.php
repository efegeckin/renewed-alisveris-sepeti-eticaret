<?php
include '../admin/baglanti.php';
session_start();
if (!isset($_SESSION['kullanici_id'])) {
    echo json_encode(['error' => 'Oturum bulunamadı.']);
    exit;
}
$kullanici_id = $_SESSION['kullanici_id'];
$adres_id = isset($_POST['adres_id']) ? intval($_POST['adres_id']) : 0;
if ($adres_id <= 0) {
    echo json_encode(['error' => 'Adres ID geçersiz.']);
    exit;
}
$sql = "SELECT a.adres_basligi, m.mahalle_adi AS mahalle, i.il_adi AS il, c.ilce_adi AS ilce, a.adres_detay, a.posta_kodu FROM kullanici_adresleri a JOIN mahalleler m ON a.mahalle_id = m.id JOIN ilceler c ON m.ilce_id = c.id JOIN iller i ON c.il_id = i.id WHERE a.kullanici_id = '$kullanici_id' AND a.id = '$adres_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
if ($adres = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'adres_basligi' => $adres['adres_basligi'],
        'il' => $adres['il'],
        'ilce' => $adres['ilce'],
        'mahalle' => $adres['mahalle'],
        'adres_detay' => $adres['adres_detay'],
        'posta_kodu' => $adres['posta_kodu']
    ]);
} else {
    echo json_encode(['error' => 'Adres bulunamadı.']);
}
