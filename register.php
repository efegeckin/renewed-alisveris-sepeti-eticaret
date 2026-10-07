<?php
session_start();
include 'admin/baglanti.php';

if (isset($_SESSION['kullanici_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = trim($_POST['ad'] ?? '');
    $soyad = trim($_POST['soyad'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordRepeat = $_POST['password_r'] ?? '';
    if ($ad === '' || $soyad === '' || $username === '') {
        $error = 'Ad, soyad ve kullanıcı adı zorunludur.';
    } elseif (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçerli bir e-posta adresi girin.';
    } elseif (strlen($password) < 8) {
        $error = 'Şifreniz en az 8 karakter olmalıdır.';
    } elseif ($password !== $passwordRepeat) {
        $error = 'Şifreler eşleşmiyor.';
    } else {
        $check = mysqli_prepare($conn, 'SELECT kullanici_id FROM kullanici WHERE username = ? OR eposta = ? LIMIT 1');
        mysqli_stmt_bind_param($check, 'ss', $username, $eposta);
        mysqli_stmt_execute($check);
        $existing = mysqli_stmt_get_result($check);
        if ($existing && mysqli_num_rows($existing) > 0) {
            $error = 'Kullanıcı adı veya e-posta zaten kayıtlı.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = mysqli_prepare($conn, "INSERT INTO kullanici (ad, soyad, eposta, username, password, img) VALUES (?, ?, ?, ?, ?, 'person.jpg')");
            mysqli_stmt_bind_param($insert, 'sssss', $ad, $soyad, $eposta, $username, $hash);
            if (mysqli_stmt_execute($insert)) {
                header('Location: login.php?registered=1');
                exit;
            }
            $error = 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.';
        }
    }
}
?>
<!doctype html>
<html lang="tr">
<head><?php include 'parts/head.php'; ?><link rel="stylesheet" href="assets/css/auth.css"></head>
<body class="auth-page">
    <main class="auth-layout">
        <section class="auth-panel auth-panel-brand auth-panel-register">
            <a class="brand brand-light" href="index.php"><span class="brand-mark">a</span><span>alışveriş<small>teknoloji seçkisi</small></span></a>
            <div class="auth-hero-copy"><span class="eyebrow">Yeni bir başlangıç</span><h1>Senin için<br><em>tasarlandı.</em></h1><p>Ürünleri kaydet, adreslerini yönet ve alışverişini kolayca tamamla.</p></div>
            <div class="auth-note"><i class="bi bi-stars"></i> Üyeliğin tamamen ücretsiz</div>
        </section>
        <section class="auth-panel auth-panel-form"><div class="auth-form-wrap"><span class="eyebrow">Topluluğa katıl</span><h2>Hesap oluştur</h2><p class="auth-muted">Sadece birkaç bilgiyle hesabın hazır.</p>
            <?php if ($error): ?><div class="auth-error"><i class="bi bi-exclamation-circle"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <form method="post" novalidate>
                <div class="auth-form-row"><label for="ad">Ad<input id="ad" name="ad" type="text" placeholder="Adın" required></label><label for="soyad">Soyad<input id="soyad" name="soyad" type="text" placeholder="Soyadın" required></label></div>
                <label for="eposta">E-posta<input id="eposta" name="eposta" type="email" autocomplete="email" placeholder="ornek@mail.com" required></label>
                <label for="username">Kullanıcı adı<input id="username" name="username" type="text" autocomplete="username" placeholder="kullanici_adin" required></label>
                <div class="auth-form-row"><label for="password">Şifre<div class="password-field"><input id="password" name="password" type="password" autocomplete="new-password" placeholder="En az 8 karakter" required><button type="button" data-toggle-password="#password" aria-label="Şifreyi göster"><i class="bi bi-eye"></i></button></div></label><label for="password_r">Tekrar<div class="password-field"><input id="password_r" name="password_r" type="password" autocomplete="new-password" placeholder="Tekrar" required><button type="button" data-toggle-password="#password_r" aria-label="Şifreyi göster"><i class="bi bi-eye"></i></button></div></label></div>
                <button class="auth-submit" type="submit">Kayıt ol <i class="bi bi-arrow-up-right"></i></button>
            </form>
            <p class="auth-switch">Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p><a class="auth-back" href="index.php"><i class="bi bi-arrow-left"></i> Mağazaya dön</a>
        </div></section>
    </main>
    <script src="assets/js/auth.js"></script>
</body>
</html>
