<?php
include 'baglanti.php';
checkAdmin();

$error = '';
$success = '';
$modelName = trim($_POST['model_ad'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($modelName === '' || mb_strlen($modelName) < 2 || mb_strlen($modelName) > 100) {
        $error = 'Model adı 2 ile 100 karakter arasında olmalıdır.';
    } else {
        $check = mysqli_prepare($conn, 'SELECT model_id FROM model WHERE LOWER(model_ad) = LOWER(?) LIMIT 1');
        mysqli_stmt_bind_param($check, 's', $modelName);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);
        if (mysqli_stmt_num_rows($check) > 0) {
            $error = 'Bu model zaten kayıtlı.';
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO model (model_ad) VALUES (?)');
            mysqli_stmt_bind_param($stmt, 's', $modelName);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: model_ekle.php?added=1');
                exit;
            }
            $error = 'Model kaydedilemedi. Lütfen tekrar deneyin.';
        }
    }
} elseif (isset($_GET['added'])) {
    $success = 'Model başarıyla eklendi.';
}

$models = mysqli_query($conn, 'SELECT model_id, model_ad FROM model ORDER BY model_id DESC');
?>
<!doctype html>
<html lang="tr">
<head><?php include 'a-parts/head.php'; ?></head>
<body>
<div class="container-fluid d-flex w-100 min-vh-100 p-0">
    <?php include 'a-parts/header.php'; ?>
    <main class="content">
        <?php include 'a-parts/content_top.php'; ?>
        <div class="model-page">
            <div class="products-heading">
                <div>
                    <span class="eyebrow">Katalog yönetimi</span>
                    <h1>Model ekle</h1>
                    <p>Yeni cihaz modellerini kataloğa ekleyin.</p>
                </div>
                <a href="telefon_ekle.php" class="admin-secondary"><i class="bi bi-arrow-left"></i> Ürün ekleme</a>
            </div>
            <?php if ($error): ?><div class="model-alert error-alert"><i class="bi bi-exclamation-circle"></i><?= htmlspecialchars($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="model-alert success-alert"><i class="bi bi-check-circle"></i><?= htmlspecialchars($success) ?></div><?php endif; ?>
            <div class="model-layout">
                <section class="model-form-card">
                    <div class="model-card-icon"><i class="bi bi-phone"></i></div>
                    <h2>Yeni model oluştur</h2>
                    <p>Model adı ürün ekleme ekranındaki seçim listesine eklenir.</p>
                    <form method="post" action="model_ekle.php">
                        <label for="model_ad">Model adı</label>
                        <div class="model-input-wrap"><i class="bi bi-phone"></i><input id="model_ad" name="model_ad" type="text" maxlength="100" value="<?= htmlspecialchars($modelName) ?>" placeholder="Örn. iPhone 17 Air" autocomplete="off" required></div>
                        <small>2–100 karakter kullanın.</small>
                        <button class="admin-primary model-submit" type="submit"><i class="bi bi-plus-lg"></i> Modeli kaydet</button>
                    </form>
                </section>
                <section class="models-list-card">
                    <div class="models-list-head"><div><span class="eyebrow">Katalog</span><h2>Kayıtlı modeller</h2></div><span class="model-count"><?= mysqli_num_rows($models) ?> model</span></div>
                    <div class="models-list"><?php if (mysqli_num_rows($models) === 0): ?><div class="models-empty">Henüz model eklenmemiş.</div><?php endif; ?><?php while ($model = mysqli_fetch_assoc($models)): ?><div class="model-row"><span class="model-row-icon"><i class="bi bi-phone"></i></span><span><b><?= htmlspecialchars($model['model_ad']) ?></b><small>Model #<?= (int)$model['model_id'] ?></small></span><i class="bi bi-check-circle-fill"></i></div><?php endwhile; ?></div>
                </section>
            </div>
        </div>
    </main>
</div>
</body>
</html>
