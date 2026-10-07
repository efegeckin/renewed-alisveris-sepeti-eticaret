<?php
session_start();
include 'admin/baglanti.php';

if (isset($_SESSION['kullanici_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username === '' || $password === '') {
        $error = 'Kullanıcı adı ve şifre alanları zorunludur.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT kullanici_id, username, password, rol FROM kullanici WHERE username = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = $result ? mysqli_fetch_assoc($result) : null;
        $passwordMatches = false;
        $legacyPassword = false;
        if ($user) {
            $passwordMatches = password_verify($password, $user['password']);
            if (!$passwordMatches && strlen($user['password']) < 60) {
                $legacyPassword = hash_equals($user['password'], $password);
                $passwordMatches = $legacyPassword;
            }
        }
        if ($user && $passwordMatches) {
            if ($legacyPassword || password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upgrade = mysqli_prepare($conn, 'UPDATE kullanici SET password = ? WHERE kullanici_id = ?');
                $userId = (int) $user['kullanici_id'];
                mysqli_stmt_bind_param($upgrade, 'si', $newHash, $userId);
                mysqli_stmt_execute($upgrade);
            }
            session_regenerate_id(true);
            $_SESSION['admin'] = $user['username'];
            $_SESSION['kullanici_id'] = (int) $user['kullanici_id'];
            $_SESSION['rol'] = $user['rol'] ?? 'kullanici';
            header('Location: index.php');
            exit;
        }
        $error = 'Kullanıcı adı veya şifre hatalı.';
    }
}
?>
<!doctype html>
<html lang="tr">
<head><?php include 'parts/head.php'; ?><link rel="stylesheet" href="assets/css/auth.css"></head>
<body class="auth-page">
    <main class="auth-layout">
        <section class="auth-panel auth-panel-brand">
            <a class="brand brand-light" href="index.php"><span class="brand-mark">a</span><span>alışveriş<small>teknoloji seçkisi</small></span></a>
            <div class="auth-hero-copy"><span class="eyebrow">Tekrar hoş geldin</span><h1>İyi teknoloji<br><em>seninle başlar.</em></h1><p>Favori ürünlerini kaydet, siparişlerini takip et ve alışveriş deneyimini kişiselleştir.</p></div>
            <div class="auth-note"><i class="bi bi-shield-check"></i> Güvenli ve hızlı alışveriş deneyimi</div>
        </section>
        <section class="auth-panel auth-panel-form">
            <div class="auth-form-wrap"><span class="eyebrow">Hesabına eriş</span><h2>Giriş yap</h2><p class="auth-muted">Alışverişe devam etmek için bilgilerini gir.</p>
                <?php if (isset($_GET['registered'])): ?><div class="auth-success"><i class="bi bi-check-circle"></i>Hesabın oluşturuldu. Şimdi giriş yapabilirsin.</div><?php endif; ?>
                <?php if ($error): ?><div class="auth-error"><i class="bi bi-exclamation-circle"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <form method="post" novalidate>
                    <label for="username">Kullanıcı adı<input id="username" name="username" type="text" autocomplete="username" placeholder="kullanici_adin" required></label>
                    <label for="password">Şifre<div class="password-field"><input id="password" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required><button type="button" data-toggle-password="#password" aria-label="Şifreyi göster"><i class="bi bi-eye"></i></button></div></label>
                    <button class="auth-submit" type="submit">Giriş yap <i class="bi bi-arrow-up-right"></i></button>
                </form>
                <p class="auth-switch">Hesabın yok mu? <a href="register.php">Kayıt ol</a></p><a class="auth-back" href="index.php"><i class="bi bi-arrow-left"></i> Mağazaya dön</a>
            </div>
        </section>
    </main>
    <script src="assets/js/auth.js"></script>
</body>
</html>
