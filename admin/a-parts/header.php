<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$isLowStockPage = $currentPage === 'telefon.php' && isset($_GET['stock']) && $_GET['stock'] === 'low';
$lowStockCount = 0;
$productCount = 0;
if (isset($conn)) {
    $productResult = mysqli_query($conn, "SELECT COUNT(*) total FROM telefon");
    $productCount = (int) (mysqli_fetch_assoc($productResult)['total'] ?? 0);
    $stockResult = mysqli_query($conn, "SELECT COUNT(*) total FROM telefon WHERE stok_adet <= 5 OR stok_id = 2");
    $lowStockCount = (int) (mysqli_fetch_assoc($stockResult)['total'] ?? 0);
}
$isActive = static function (array $pages) use ($currentPage): string {
    return in_array($currentPage, $pages, true) ? ' active' : '';
};
?>
<button class="admin-menu-toggle" type="button" aria-label="Menüyü aç" aria-expanded="false"><i class="bi bi-list"></i></button>
<aside class="left-bar" id="adminSidebar">
    <div class="admin-brand">
        <a href="admin.php" aria-label="Admin ana sayfa"><img src="../assets/img/logo_trim.png" alt="Alışveriş"></a>
        <span>YÖNETİM</span>
    </div>
    <nav class="admin-nav" aria-label="Yönetim menüsü">
        <span class="admin-nav-label">Genel</span>
        <a class="nav-link<?= $isActive(['admin.php', 'sablon.php']) ?>" href="admin.php"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a>
        <a class="nav-link<?= $isActive(['ayarlar.php']) ?>" href="ayarlar.php"><i class="bi bi-sliders2"></i><span>Ayarlar</span></a>
        <span class="admin-nav-label">Katalog</span>
        <a class="nav-link<?= (!$isLowStockPage && in_array($currentPage, ['urunler.php', 'telefon.php'], true)) ? ' active' : '' ?>" href="telefon.php"><i class="bi bi-box-seam"></i><span>Ürünler</span><em><?= $productCount ?></em></a>
        <a class="nav-link<?= $isActive(['telefon_ekle.php']) ?>" href="telefon_ekle.php"><i class="bi bi-plus-circle"></i><span>Ürün ekle</span></a>
        <a class="nav-link<?= $isActive(['model_ekle.php']) ?>" href="model_ekle.php"><i class="bi bi-tags"></i><span>Model ekle</span></a>
        <span class="admin-nav-label">Operasyon</span>
        <a class="nav-link external-item" href="../siparisler.php"><i class="bi bi-receipt"></i><span>Siparişler</span></a>
        <a class="nav-link<?= $isActive(['kullanicilar.php']) ?>" href="kullanicilar.php"><i class="bi bi-people"></i><span>Kullanıcılar</span></a>
        <a class="nav-link<?= $isLowStockPage ? ' active' : '' ?>" href="telefon.php?stock=low"><i class="bi bi-exclamation-triangle"></i><span>Stok uyarıları</span><?php if ($lowStockCount): ?><em class="warning-count"><?= $lowStockCount ?></em><?php endif; ?></a>
    </nav>
    <div class="admin-sidebar-bottom">
        <a href="../index.php" class="store-link"><i class="bi bi-shop"></i><span>Mağazayı görüntüle</span><i class="bi bi-box-arrow-up-right"></i></a>
        <a href="../logout.php" class="logout-link"><i class="bi bi-box-arrow-left"></i><span>Oturumu kapat</span></a>
    </div>
</aside>
<div class="admin-sidebar-backdrop"></div>
<script>
(() => {
    const toggle = document.querySelector('.admin-menu-toggle');
    const sidebar = document.getElementById('adminSidebar');
    const backdrop = document.querySelector('.admin-sidebar-backdrop');
    if (!toggle || !sidebar) return;
    const setOpen = (open) => {
        sidebar.classList.toggle('is-open', open);
        document.body.classList.toggle('admin-menu-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    };
    toggle.addEventListener('click', () => setOpen(!sidebar.classList.contains('is-open')));
    if (backdrop) backdrop.addEventListener('click', () => setOpen(false));
})();
</script>
