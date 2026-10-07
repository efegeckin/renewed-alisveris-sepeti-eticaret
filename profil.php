<?php
include 'admin/baglanti.php';
checkAuth();
$userId = (int) $_SESSION['kullanici_id'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['adres_basligi'] ?? '');
    $neighborhood = (int) ($_POST['mahalle_id'] ?? 0);
    $detail = trim($_POST['adres_detay'] ?? '');
    $postcode = trim($_POST['posta_kodu'] ?? '');
    if ($title === '' || !$neighborhood || $detail === '' || !preg_match('/^\d{5}$/', $postcode)) {
        $error = 'Adres bilgilerini eksiksiz ve doğru girin.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO kullanici_adresleri (kullanici_id, adres_basligi, mahalle_id, adres_detay, posta_kodu) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'isiss', $userId, $title, $neighborhood, $detail, $postcode);
        if (mysqli_stmt_execute($stmt)) { header('Location: profil.php?saved=1'); exit; }
        $error = 'Adres kaydedilemedi. Lütfen tekrar deneyin.';
    }
}
$profileResult = mysqli_query($conn, "SELECT ad, soyad, eposta, username, img, rol FROM kullanici WHERE kullanici_id=$userId");
$profile = mysqli_fetch_assoc($profileResult);
$addressResult = mysqli_query($conn, "SELECT a.*, m.mahalle_adi AS mahalle, c.ilce_adi AS ilce, i.il_adi AS il FROM kullanici_adresleri a JOIN mahalleler m ON m.id=a.mahalle_id JOIN ilceler c ON c.id=m.ilce_id JOIN iller i ON i.id=c.il_id WHERE a.kullanici_id=$userId ORDER BY a.id DESC");
$addresses = [];
while ($address = mysqli_fetch_assoc($addressResult)) $addresses[] = $address;
$ordersTable = mysqli_query($conn, "SHOW TABLES LIKE 'siparisler'");
$orderCount = 0;
if ($ordersTable && mysqli_num_rows($ordersTable)) { $orderResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM siparisler WHERE kullanici_id=$userId"); $orderCount = (int) (mysqli_fetch_assoc($orderResult)['total'] ?? 0); }
$cities = mysqli_query($conn, "SELECT * FROM iller ORDER BY il_adi");
?>
<!doctype html>
<html lang="tr"><head><?php include 'parts/head.php'; ?><link rel="stylesheet" href="assets/css/account.css"></head><body>
<?php include 'parts/header.php'; ?>
<main class="account-page profile-page"><div class="page-intro"><div><span class="eyebrow">Hesap merkezi</span><h1>Profilim</h1><p>Bilgilerin, adreslerin ve siparişlerin tek yerde.</p></div><a class="text-action" href="logout.php"><i class="bi bi-box-arrow-right"></i> Çıkış yap</a></div>
<?php if (isset($_GET['saved'])): ?><div class="account-alert success-alert"><i class="bi bi-check-circle"></i> Adresin başarıyla kaydedildi.</div><?php endif; ?><?php if ($error): ?><div class="account-alert error-alert"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
<div class="profile-grid"><section class="profile-card identity-card"><div class="profile-avatar"><?= strtoupper(substr($profile['ad'] ?? 'A', 0, 1)) ?></div><span class="eyebrow">Kişisel bilgiler</span><h2><?= htmlspecialchars(($profile['ad'] ?? '').' '.($profile['soyad'] ?? '')) ?></h2><p class="profile-username">@<?= htmlspecialchars($profile['username'] ?? '') ?></p><dl><div><dt>E-posta</dt><dd><?= htmlspecialchars($profile['eposta'] ?? '') ?></dd></div><div><dt>Hesap türü</dt><dd><?= ($profile['rol'] ?? '') === 'admin' ? 'Yönetici' : 'Standart üye' ?></dd></div></dl><a href="siparisler.php" class="profile-stat"><span><i class="bi bi-box-seam"></i> Siparişlerim</span><b><?= $orderCount ?><i class="bi bi-arrow-up-right"></i></b></a></section>
<section class="profile-card addresses-card"><div class="card-heading"><div><span class="eyebrow">Teslimat</span><h2>Adreslerim</h2></div><button class="icon-button" data-bs-toggle="modal" data-bs-target="#addressModal"><i class="bi bi-plus-lg"></i></button></div><?php if (!$addresses): ?><div class="mini-empty"><i class="bi bi-geo-alt"></i><p>Henüz kayıtlı adresin yok.</p><button class="btn btn-brand btn-sm" data-bs-toggle="modal" data-bs-target="#addressModal">Adres ekle</button></div><?php else: ?><div class="address-list"><?php foreach ($addresses as $address): ?><article class="address-item"><div class="address-icon"><i class="bi bi-house"></i></div><div><b><?= htmlspecialchars($address['adres_basligi']) ?></b><p><?= htmlspecialchars($address['il'].' / '.$address['ilce'].' / '.$address['mahalle']) ?></p><small><?= htmlspecialchars($address['adres_detay']) ?> · <?= htmlspecialchars($address['posta_kodu']) ?></small></div></article><?php endforeach; ?></div><?php endif; ?></section></div></main>
<div class="modal fade" id="addressModal" tabindex="-1"><div class="modal-dialog modal-dialog-centered"><div class="modal-content account-modal"><div class="modal-header"><div><span class="eyebrow">Yeni teslimat noktası</span><h2>Adres ekle</h2></div><button class="btn-close" data-bs-dismiss="modal"></button></div><form method="post"><div class="modal-body"><label>Adres başlığı<input name="adres_basligi" placeholder="Ev, iş..." required></label><div class="form-two"><label>Şehir<select name="sehir" id="sehir" required><option value="">Seçiniz</option><?php while($city=mysqli_fetch_assoc($cities)): ?><option value="<?= (int)$city['id'] ?>"><?= htmlspecialchars($city['il_adi']) ?></option><?php endwhile; ?></select></label><label class="ilce" style="display:none">İlçe<select name="ilce_id" id="ilce"><option value="">Seçiniz</option></select></label></div><label class="mahalle" style="display:none">Mahalle<select name="mahalle_id" id="mahalle"><option value="">Seçiniz</option></select></label><label>Adres<textarea name="adres_detay" rows="3" placeholder="Cadde, sokak, bina ve daire no" required></textarea></label><label>Posta kodu<input name="posta_kodu" inputmode="numeric" pattern="\d{5}" maxlength="5" placeholder="34000" required></label></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Vazgeç</button><button class="btn btn-brand" type="submit">Adresi kaydet</button></div></form></div></div></div>
<?php include 'parts/footer.php'; ?><script src="assets/js/app.js"></script></body></html>
