<header class="content-top">
    <div class="admin-page-context"><span class="admin-live-dot"></span><span>Yönetim paneli</span><strong><?= htmlspecialchars($_SESSION['admin'] ?? 'Yönetici') ?></strong></div>
    <div class="admin-top-actions">
        <a class="admin-top-link" href="../index.php"><i class="bi bi-eye"></i> Mağazayı gör</a>
        <div class="dropdown">
            <button class="admin-notification" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Bildirimler"><i class="bi bi-bell"></i><span>!</span></button>
            <div class="dropdown-menu dropdown-menu-end admin-notification-menu">
                <div class="notification-title"><b>Bildirimler</b><small>Son durum</small></div>
                <div class="notification-item"><i class="bi bi-box-seam"></i><span><b>Stok kontrolü</b><small>Ürün stoklarını düzenli takip edin.</small></span></div>
                <div class="notification-item"><i class="bi bi-shield-check"></i><span><b>Güvenli oturum</b><small>Yönetici olarak giriş yaptınız.</small></span></div>
            </div>
        </div>
        <div class="admin-avatar"><?= strtoupper(substr($_SESSION['admin'] ?? 'A', 0, 1)) ?></div>
    </div>
</header>