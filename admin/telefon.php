<?php
include 'baglanti.php';
checkAdmin();
$query = trim($_GET['q'] ?? '');
$lowStock = ($_GET['stock'] ?? '') === 'low';
$conditions = [];
$params = [];
$types = '';
if ($query !== '') {
    $conditions[] = '(m.model_ad LIKE ? OR r.renk_ad LIKE ? OR h.hafiza_ad LIKE ?)';
    $like = '%' . $query . '%';
    $params = [$like, $like, $like];
    $types = 'sss';
}
if ($lowStock) $conditions[] = '(t.stok_adet <= 5 OR t.stok_id = 2)';
$sql = "SELECT t.id,t.img,t.fiyat,t.stok_adet,t.stok_id,m.model_ad,h.hafiza_ad,r.renk_ad FROM telefon t LEFT JOIN model m ON m.model_id=t.model_id LEFT JOIN hafiza h ON h.hafiza_id=t.hafiza_id LEFT JOIN renkler r ON r.renk_id=t.renk_id";
if ($conditions) $sql .= ' WHERE ' . implode(' AND ', $conditions);
$sql .= ' ORDER BY t.id DESC';
$stmt = mysqli_prepare($conn, $sql);
if ($params) mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
?>
<!doctype html>
<html lang="tr"><head><?php include 'a-parts/head.php'; ?></head><body>
<div class="container-fluid d-flex w-100 min-vh-100 p-0"><?php include 'a-parts/header.php'; ?><main class="content"><?php include 'a-parts/content_top.php'; ?>
<div class="products-page"><div class="products-heading"><div><span class="eyebrow">Katalog yönetimi</span><h1><?= $lowStock ? 'Stok uyarıları' : 'Ürünler' ?></h1><p><?= $lowStock ? 'Azalan ve tükenen ürünleri takip et.' : 'Tüm ürünlerini tek yerden yönet.' ?></p></div><a class="admin-primary" href="telefon_ekle.php"><i class="bi bi-plus-lg"></i> Yeni ürün</a></div>
<div class="products-toolbar"><form method="get" class="product-search"><i class="bi bi-search"></i><input type="search" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Model, renk veya hafıza ara..."><button type="submit">Ara</button><?php if($lowStock): ?><input type="hidden" name="stock" value="low"><?php endif; ?></form><div class="product-filter-links"><a class="<?= !$lowStock ? 'selected' : '' ?>" href="telefon.php">Tüm ürünler</a><a class="<?= $lowStock ? 'selected warning-filter' : '' ?>" href="telefon.php?stock=low"><i class="bi bi-exclamation-triangle"></i> Düşük stok</a></div></div>
<section class="products-card"><div class="products-card-head"><div><h2><?= mysqli_num_rows($products) ?> ürün</h2><span>Güncel katalog listesi</span></div><span class="product-sort"><i class="bi bi-sort-down"></i> En yeni</span></div><div class="products-table"><div class="products-table-row products-table-header"><span>Ürün</span><span>Varyant</span><span>Fiyat</span><span>Stok</span><span>İşlem</span></div><?php if(mysqli_num_rows($products) === 0): ?><div class="products-empty"><i class="bi bi-search"></i><h3>Ürün bulunamadı</h3><p>Arama kriterlerini değiştirip tekrar deneyin.</p></div><?php endif; ?><?php while($product=mysqli_fetch_assoc($products)): $available=(int)$product['stok_adet'] > 0 && (int)$product['stok_id'] !== 2; ?><div class="products-table-row"><div class="product-main"><img src="../assets/img/<?= htmlspecialchars($product['img']) ?>" alt=""><span><b><?= htmlspecialchars($product['model_ad'] ?? 'İsimsiz ürün') ?></b><small>#<?= (int)$product['id'] ?> · <?= htmlspecialchars($product['hafiza_ad'] ?? '—') ?></small></span></div><div class="product-variant"><span class="color-dot"></span><?= htmlspecialchars($product['renk_ad'] ?? '—') ?><small><?= htmlspecialchars($product['hafiza_ad'] ?? '—') ?></small></div><strong class="product-price"><?= number_format((float)$product['fiyat'], 2, ',', '.') ?> TL</strong><div><span class="stock-pill <?= $available ? 'available' : 'empty' ?>"><?= $available ? 'Stokta' : 'Tükendi' ?></span><small class="stock-count"><?= (int)$product['stok_adet'] ?> adet</small></div><form method="post" action="telefon_sil.php" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');"><input type="hidden" name="id" value="<?= (int)$product['id'] ?>"><button class="icon-action delete-action" type="submit" aria-label="Ürünü sil"><i class="bi bi-trash3"></i></button></form></div><?php endwhile; ?></div></section></div></main></div></body></html>
