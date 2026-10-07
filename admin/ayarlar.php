<?php
include 'baglanti.php';
checkAdmin();
$dbOk = $conn && mysqli_ping($conn);
?>
<!doctype html>
<html lang="tr"><head><?php include 'a-parts/head.php'; ?></head><body>
<div class="container-fluid d-flex w-100 min-vh-100 p-0"><?php include 'a-parts/header.php'; ?><main class="content"><?php include 'a-parts/content_top.php'; ?>
<div class="admin-form-page"><div class="admin-heading"><div><span class="eyebrow">Sistem</span><h1>Ayarlar</h1><p>Mağaza ve yönetim paneli hakkında genel bilgiler.</p></div></div>
<div class="settings-grid"><section class="settings-card"><div class="settings-icon purple"><i class="bi bi-database-check"></i></div><div><h2>Veritabanı bağlantısı</h2><p>Mağaza verileri aktif ve erişilebilir durumda.</p></div><span class="settings-status <?= $dbOk ? 'ok' : 'error' ?>"><i class="bi bi-circle-fill"></i> <?= $dbOk ? 'Bağlı' : 'Bağlantı hatası' ?></span></section>
<section class="settings-card"><div class="settings-icon blue"><i class="bi bi-shield-lock"></i></div><div><h2>Yönetici güvenliği</h2><p>Bu alan yalnızca <b>admin</b> rolüne sahip kullanıcılar tarafından görüntülenebilir.</p></div><span class="settings-status ok"><i class="bi bi-check-circle-fill"></i> Aktif</span></section>
<section class="settings-card"><div class="settings-icon orange"><i class="bi bi-image"></i></div><div><h2>Ürün görselleri</h2><p>JPG, PNG ve WEBP formatları desteklenir. Maksimum dosya boyutu 5 MB.</p></div><a class="settings-action" href="telefon_ekle.php">Ürün ekle <i class="bi bi-arrow-up-right"></i></a></section>
<section class="settings-card"><div class="settings-icon green"><i class="bi bi-shop"></i></div><div><h2>Mağaza görünümü</h2><p>Yayındaki mağazayı yeni sekmede açarak değişiklikleri kontrol edin.</p></div><a class="settings-action" href="../index.php">Mağazayı aç <i class="bi bi-box-arrow-up-right"></i></a></section></div>
</div></main></div></body></html>
