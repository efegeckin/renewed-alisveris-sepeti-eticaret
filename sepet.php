<?php
include 'admin/baglanti.php';
checkAuth();
$userId = (int) $_SESSION['kullanici_id'];
$items = mysqli_query($conn, "SELECT s.sepet_kalem_id, s.adet, t.img, t.fiyat, t.stok_adet, t.stok_id, m.model_ad, h.hafiza_ad, r.renk_ad FROM sepet s JOIN telefon t ON t.id=s.telefon_id LEFT JOIN model m ON m.model_id=t.model_id LEFT JOIN hafiza h ON h.hafiza_id=t.hafiza_id LEFT JOIN renkler r ON r.renk_id=t.renk_id WHERE s.kullanici_id=$userId ORDER BY s.sepet_kalem_id DESC");
$cartItems = [];
$total = 0;
$count = 0;
while ($item = mysqli_fetch_assoc($items)) {
    $item['line_total'] = (float) $item['fiyat'] * (int) $item['adet'];
    $cartItems[] = $item;
    $total += $item['line_total'];
    $count += (int) $item['adet'];
}
?>
<!doctype html>
<html lang="tr"><head><?php include 'parts/head.php'; ?><link rel="stylesheet" href="assets/css/account.css"></head><body>
<?php include 'parts/header.php'; ?>
<main class="account-page cart-page">
    <div class="page-intro"><div><span class="eyebrow">Alışveriş sepeti</span><h1>Sepetin <span>(<?= $count ?> ürün)</span></h1><p>Seçtiklerin burada seni bekliyor.</p></div><?php if ($cartItems): ?><a class="text-action danger-action" href="sepet_temizle.php" onclick="return confirm('Sepetindeki tüm ürünler silinsin mi?')"><i class="bi bi-trash3"></i> Sepeti temizle</a><?php endif; ?></div>
    <?php if (!$cartItems): ?>
        <section class="empty-panel"><div class="empty-icon"><i class="bi bi-bag"></i></div><h2>Sepetin şu an boş</h2><p>İlham veren teknoloji ürünlerini keşfetmeye ne dersin?</p><a class="btn btn-brand" href="telefon.php">Alışverişe başla <i class="bi bi-arrow-up-right"></i></a></section>
    <?php else: ?>
        <div class="cart-layout"><section class="cart-items"><div class="cart-panel-head"><b>Ürünler</b><span><?= count($cartItems) ?> farklı ürün</span></div><?php foreach ($cartItems as $item): ?><article class="cart-item"><div class="cart-product-image"><img src="assets/img/<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['model_ad']) ?>"></div><div class="cart-product-info"><span class="cart-label"><?= htmlspecialchars($item['stok_id'] == 2 ? 'Stok tükendi' : 'Hızlı teslimat') ?></span><h2><?= htmlspecialchars(trim($item['model_ad'].' '.$item['hafiza_ad'])) ?></h2><p><?= htmlspecialchars($item['renk_ad'] ?? '') ?> · <?= (int)$item['adet'] ?> adet</p><small><?= htmlspecialchars($item['stok_id'] == 2 ? 'Bu ürün stokta değil.' : 'Ücretsiz kargo ve güvenli paketleme') ?></small></div><div class="cart-item-price"><strong><?= number_format($item['line_total'], 2, ',', '.') ?> TL</strong><span><?= number_format((float)$item['fiyat'], 2, ',', '.') ?> TL / adet</span><a href="sepet_sil.php?sepet_kalem_id=<?= (int)$item['sepet_kalem_id'] ?>&confirm=1" onclick="return confirm('Bu ürünü sepetten çıkarılsın mı?')"><i class="bi bi-x-lg"></i> Kaldır</a></div></article><?php endforeach; ?></section>
            <aside class="summary-card"><span class="eyebrow">Sipariş özeti</span><h2>Toplam</h2><div class="summary-row"><span>Ürünler (<?= $count ?>)</span><b><?= number_format($total, 2, ',', '.') ?> TL</b></div><div class="summary-row"><span>Kargo</span><b class="success-text">Ücretsiz</b></div><hr><div class="summary-total"><span>Ödenecek tutar</span><strong><?= number_format($total, 2, ',', '.') ?> TL</strong></div><a class="btn btn-brand summary-button" href="odeme.php">Ödemeye geç <i class="bi bi-arrow-right"></i></a><div class="secure-note"><i class="bi bi-shield-check"></i> Güvenli ödeme · 14 gün kolay iade</div></aside>
        </div>
    <?php endif; ?>
</main>
<?php include 'parts/footer.php'; ?></body></html>
