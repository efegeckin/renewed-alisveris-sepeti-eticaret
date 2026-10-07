<?php
include '../admin/baglanti.php';

session_start();
if (!isset($_SESSION['kullanici_id'])) {
    echo '<p>Oturum bulunamadı.</p>';
    exit;
}

$kullanici_id = $_SESSION['kullanici_id'];
$adresler = mysqli_query($conn, "SELECT a.adres_basligi, m.mahalle_adi AS mahalle, i.il_adi AS il, c.ilce_adi AS ilce, a.adres_detay, a.posta_kodu FROM kullanici_adresleri a JOIN mahalleler m ON a.mahalle_id = m.id JOIN ilceler c ON m.ilce_id = c.id JOIN iller i ON c.il_id = i.id WHERE a.kullanici_id = '$kullanici_id'");
if (mysqli_num_rows($adresler) > 0) {
    while ($adres = mysqli_fetch_assoc($adresler)) {
        echo "<div class='adres-card mb-4 p-3 border rounded position-relative'>";
        echo "<p>Adres Başlığı: <b>" . htmlspecialchars($adres['adres_basligi']) . "</b></p>";
        echo "<p>Şehir: <b>" . htmlspecialchars($adres['il'] . " / " . $adres['ilce'] . " / " . $adres['mahalle']) . "</b></p>";
        echo "<p>Adres:  <b>" . htmlspecialchars($adres['adres_detay']) . "</b></p>";
        echo "<p>Posta Kodu: <b>" . htmlspecialchars($adres['posta_kodu']) . "</b></p>";
        echo '<button type="button" class="btn text-danger sil position-absolute top-0 end-0 m-2 btn-sm"><i class="bi bi-trash"></i></button>';
        echo '<button type="button" class="btn text-warning düzenle position-absolute bottom-0 end-0 m-2 btn-sm"><i class="bi bi-pencil-square"></i></button>';
        echo "</div>";
    }
} else {
    echo "<p>Henüz adres eklenmemiş.</p>";
}
