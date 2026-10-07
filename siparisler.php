<?php
include 'admin/baglanti.php';
checkAuth();
$userId = (int) $_SESSION['kullanici_id'];
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS siparisler (
    siparis_id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    toplam_tutar DECIMAL(10,2) NOT NULL,
    odeme_secimi VARCHAR(40) NOT NULL,
    durum VARCHAR(30) NOT NULL DEFAULT 'Hazırlanıyor',
    olusturma_tarihi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$orders = mysqli_query($conn, "SELECT * FROM siparisler WHERE kullanici_id = $userId ORDER BY siparis_id DESC");
?>
<!doctype html>
<html lang="tr"><head><?php include 'parts/head.php'; ?></head><body>
<?php include 'parts/header.php'; ?>
<main class="section-block">
    <div class="section-heading"><div><span class="eyebrow">Hesabım</span><h2>Siparişlerim</h2></div><a href="telefon.php">Alışverişe devam et <i class="bi bi-arrow-up-right"></i></a></div>
    <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Siparişiniz başarıyla oluşturuldu.</div><?php endif; ?>
    <?php if (mysqli_num_rows($orders) === 0): ?><div class="alert alert-light border">Henüz siparişiniz bulunmuyor.</div>
    <?php else: ?><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Sipariş</th><th>Tarih</th><th>Ödeme</th><th>Durum</th><th class="text-end">Tutar</th></tr></thead><tbody>
    <?php while ($order = mysqli_fetch_assoc($orders)): ?><tr><td>#<?= (int) $order['siparis_id'] ?></td><td><?= htmlspecialchars(date('d.m.Y H:i', strtotime($order['olusturma_tarihi']))) ?></td><td><?= $order['odeme_secimi'] === 'aninda_havale' ? 'Anında havale' : 'Taksitli ödeme' ?></td><td><span class="badge text-bg-warning"><?= htmlspecialchars($order['durum']) ?></span></td><td class="text-end"><?= number_format((float) $order['toplam_tutar'], 2, ',', '.') ?> TL</td></tr><?php endwhile; ?></tbody></table></div><?php endif; ?>
</main>
<?php include 'parts/footer.php'; ?></body></html>
