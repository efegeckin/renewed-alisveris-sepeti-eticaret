<?php
$userId = (int) ($_SESSION['kullanici_id'] ?? 0);
$cartCount = 0;
if ($userId) {
    $cartResult = mysqli_query($conn, "SELECT COALESCE(SUM(adet), 0) AS toplam_adet FROM sepet WHERE kullanici_id = $userId");
    $cartCount = (int) (mysqli_fetch_assoc($cartResult)['toplam_adet'] ?? 0);
}
?>
<header class="site-header">
    <div class="announcement"><span><i class="bi bi-lightning-charge-fill"></i> Yeni sezon fırsatları başladı</span><span class="announcement-right">Ücretsiz kargo · Güvenli ödeme · 7/24 destek</span></div>
    <div class="header-main">
        <a class="brand" href="index.php" aria-label="Alışveriş ana sayfa"><span class="brand-mark">a</span><span>alışveriş<small>teknoloji seçkisi</small></span></a>
        <form class="search-form" action="telefon.php" method="get"><i class="bi bi-search"></i><input name="q" type="search" placeholder="Ne arıyorsun?" aria-label="Ürün ara"><kbd>⌘ K</kbd></form>
        <div class="header-actions">
            <a href="sepet.php" class="header-action"><span class="icon-wrap"><i class="bi bi-bag"></i><?php if ($cartCount): ?><b><?= $cartCount > 9 ? '9+' : $cartCount ?></b><?php endif; ?></span><span>Sepetim</span></a>
            <div class="dropdown"><button class="header-action account-action" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person"></i><span><?= $userId ? 'Hesabım' : 'Giriş yap' ?></span><i class="bi bi-chevron-down chevron"></i></button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php if ($userId): ?><li><a class="dropdown-item" href="profil.php"><i class="bi bi-person"></i> Profilim</a></li><li><a class="dropdown-item" href="siparisler.php"><i class="bi bi-box-seam"></i> Siparişlerim</a></li><?php if (($_SESSION['rol'] ?? '') === 'admin'): ?><li><a class="dropdown-item" href="admin/admin.php"><i class="bi bi-speedometer2"></i> Admin paneli</a></li><?php endif; ?><li><hr class="dropdown-divider"></li><li><a class="dropdown-item text-danger" href="logout.php">Çıkış yap</a></li>
                    <?php else: ?><li><a class="dropdown-item" href="login.php">Giriş yap</a></li><li><a class="dropdown-item" href="register.php">Hesap oluştur</a></li><?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <nav class="category-nav"><a href="index.php" class="nav-home"><i class="bi bi-grid"></i> Keşfet</a><?php $categories = mysqli_query($conn, "SELECT * FROM kategori ORDER BY kategori_id ASC"); while ($category = mysqli_fetch_assoc($categories)): ?><a href="<?= htmlspecialchars($category['kategori_link']) ?>"><?= htmlspecialchars($category['kategori_ad']) ?></a><?php endwhile; ?><a href="telefon.php?sort=campaign" class="nav-highlight"><i class="bi bi-fire"></i> Fırsatlar</a><a href="admin/admin.php" class="nav-admin ms-auto"><i class="bi bi-sliders"></i> Yönetim</a></nav>
</header>
