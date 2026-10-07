<?php
include 'admin/baglanti.php';
session_start();
$search = trim($_GET['q'] ?? '');
$searchEscaped = mysqli_real_escape_string($conn, $search);
$where = $search !== '' ? "WHERE m.model_ad LIKE '%$searchEscaped%' OR t.satici LIKE '%$searchEscaped%'" : '';
$products = mysqli_query($conn, "SELECT t.*, m.model_ad, h.hafiza_ad, r.renk_ad FROM telefon t LEFT JOIN model m ON m.model_id=t.model_id LEFT JOIN hafiza h ON h.hafiza_id=t.hafiza_id LEFT JOIN renkler r ON r.renk_id=t.renk_id $where ORDER BY t.puan DESC, t.id DESC");
?>
<!doctype html>
<html lang="tr"><head><?php include 'parts/head.php'; ?></head><body>
<?php include 'parts/header.php'; ?>
<main class="catalog-page">
    <div class="catalog-head"><div><span class="eyebrow">Alışveriş seçkisi</span><h1><?= $search !== '' ? htmlspecialchars($search) . ' sonuçları' : 'Akıllı telefonlar' ?></h1><p><?= mysqli_num_rows($products) ?> ürün · Sana uygun teknolojiyi keşfet</p></div><div class="catalog-sort"><i class="bi bi-sliders"></i> En yüksek puan</div></div>
    <div class="catalog-layout">
        <aside class="catalog-filters"><div class="filter-head"><b>Filtrele</b><i class="bi bi-sliders2"></i></div><div class="filter-group"><span>Kategori</span><?php $categories=mysqli_query($conn,"SELECT * FROM kategori ORDER BY kategori_id ASC"); while($category=mysqli_fetch_assoc($categories)): ?><a href="<?= htmlspecialchars($category['kategori_link']) ?>"><?= htmlspecialchars($category['kategori_ad']) ?><i class="bi bi-chevron-right"></i></a><?php endwhile; ?></div><div class="filter-group"><span>Hafıza</span><?php $memories=mysqli_query($conn,"SELECT * FROM hafiza ORDER BY hafiza_id DESC"); while($memory=mysqli_fetch_assoc($memories)): ?><a href="telefon.php"><?= htmlspecialchars($memory['hafiza_ad']) ?><i class="bi bi-plus"></i></a><?php endwhile; ?></div><a class="filter-clear" href="telefon.php">Filtreleri temizle</a></aside>
        <section class="catalog-results"><div class="catalog-mobile-filter"><i class="bi bi-sliders2"></i> Filtreler</div><div class="catalog-grid">
        <?php while ($product=mysqli_fetch_assoc($products)): $rating=(float)$product['puan']; ?>
            <article class="product-card catalog-card"><a class="product-card-link" href="detay.php?id=<?= (int)$product['id'] ?>"><div class="product-image-wrap"><span class="product-badge"><?= (int)$product['stok_id'] === 2 ? 'Tükendi' : 'Öne çıkan' ?></span><img src="assets/img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['model_ad'].' '.$product['hafiza_ad']) ?>" loading="lazy"><span class="product-quick"><i class="bi bi-arrow-up-right"></i></span></div><div class="product-content"><div class="product-rating"><?php for($i=1;$i<=5;$i++): ?><i class="bi <?= $i<=round($rating) ? 'bi-star-fill' : 'bi-star' ?>"></i><?php endfor; ?><span><?= number_format($rating,1,',','.') ?></span></div><h3><?= htmlspecialchars($product['model_ad'].' '.$product['hafiza_ad']) ?></h3><p class="product-meta"><?= htmlspecialchars($product['renk_ad'] ?? 'Teknoloji') ?> · <?= htmlspecialchars($product['stok_id'] === '2' ? 'Stokta yok' : 'Hızlı teslimat') ?></p><strong class="product-price"><?= number_format((float)$product['fiyat'],0,',','.') ?> TL</strong></div></a></article>
        <?php endwhile; ?>
        </div><?php if (mysqli_num_rows($products)===0): ?><div class="empty-state"><i class="bi bi-search"></i><h2>Ürün bulamadık</h2><p>Arama kelimeni değiştirerek tekrar dene.</p><a href="telefon.php" class="btn btn-brand">Tüm ürünleri gör</a></div><?php endif; ?></section>
    </div>
</main>
<?php include 'parts/footer.php'; ?>
</body></html>
