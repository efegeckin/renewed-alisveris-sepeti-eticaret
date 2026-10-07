<?php
include 'baglanti.php';
checkAdmin();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = (int) ($_POST['model_id'] ?? 0);
    $price = (float) ($_POST['fiyat'] ?? 0);
    $seller = trim($_POST['satici'] ?? '');
    $color = (int) ($_POST['renk_id'] ?? 0);
    $memory = (int) ($_POST['hafiza_id'] ?? 0);
    $delivery = trim($_POST['teslimat'] ?? '');
    $payment = trim($_POST['odeme_secenekleri'] ?? '');
    $installment = trim($_POST['taksit_secenekleri'] ?? '');
    $stock = (int) ($_POST['stok_id'] ?? 0);
    $stockCount = max(0, (int) ($_POST['stok_adet'] ?? 0));
    $category = (int) ($_POST['kategori_id'] ?? 0);
    $rating = min(5, max(0, (float) ($_POST['puan'] ?? 0)));
    $image = '';
    $allowed = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
    if ($model <= 0 || $price <= 0 || $seller === '' || $color <= 0 || $memory <= 0 || $delivery === '' || $payment === '' || $stock <= 0 || $category <= 0) {
        $error = 'Lütfen tüm zorunlu alanları doldurun.';
    } elseif (!isset($_FILES['img']) || $_FILES['img']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Geçerli bir ürün görseli yükleyin.';
    } else {
        $mime = mime_content_type($_FILES['img']['tmp_name']);
        if (!isset($allowed[$mime]) || $_FILES['img']['size'] > 5 * 1024 * 1024) {
            $error = 'Görsel JPG, PNG veya WEBP olmalı ve 5 MB altında olmalıdır.';
        } else {
            $image = uniqid('product_', true) . '.' . $allowed[$mime];
            if (!move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $image)) $error = 'Görsel kaydedilemedi.';
        }
    }
    if ($error === '') {
        $stmt = mysqli_prepare($conn, "INSERT INTO telefon (model_id,img,fiyat,satici,renk_id,hafiza_id,teslimat,odeme_secenekleri,taksit_secenekleri,stok_id,stok_adet,kategori_id,puan) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'isdsiisssiiid', $model, $image, $price, $seller, $color, $memory, $delivery, $payment, $installment, $stock, $stockCount, $category, $rating);
        if (mysqli_stmt_execute($stmt)) { header('Location: telefon.php?added=1'); exit; }
        $error = 'Ürün kaydedilemedi: ' . mysqli_error($conn);
    }
}
$models=mysqli_query($conn,"SELECT * FROM model ORDER BY model_ad"); $colors=mysqli_query($conn,"SELECT * FROM renkler ORDER BY renk_ad"); $memories=mysqli_query($conn,"SELECT * FROM hafiza ORDER BY hafiza_id"); $stocks=mysqli_query($conn,"SELECT * FROM stok_durumu ORDER BY stok_id"); $categories=mysqli_query($conn,"SELECT * FROM kategori ORDER BY kategori_ad");
?>
<!doctype html>
<html lang="tr"><head><?php include 'a-parts/head.php'; ?></head><body><div class="container-fluid d-flex w-100 min-vh-100 p-0"><?php include 'a-parts/header.php'; ?><main class="content"><?php include 'a-parts/content_top.php'; ?><div class="admin-form-page"><div class="admin-heading"><div><span class="eyebrow">Katalog yönetimi</span><h1>Yeni ürün ekle</h1><p>Ürün bilgilerini girerek mağazaya ekle.</p></div><a href="telefon.php" class="admin-secondary"><i class="bi bi-arrow-left"></i> Ürünlere dön</a></div><?php if($error): ?><div class="account-alert error-alert"><i class="bi bi-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?><form class="admin-form-card" method="post" enctype="multipart/form-data"><div class="admin-form-grid"><section><h2>Ürün bilgileri</h2><label>Model<select name="model_id" required><option value="">Model seçin</option><?php while($row=mysqli_fetch_assoc($models)): ?><option value="<?= (int)$row['model_id'] ?>"><?= htmlspecialchars($row['model_ad']) ?></option><?php endwhile; ?></select></label><div class="form-two"><label>Renk<select name="renk_id" required><option value="">Renk seçin</option><?php while($row=mysqli_fetch_assoc($colors)): ?><option value="<?= (int)$row['renk_id'] ?>"><?= htmlspecialchars($row['renk_ad']) ?></option><?php endwhile; ?></select></label><label>Hafıza<select name="hafiza_id" required><option value="">Hafıza seçin</option><?php while($row=mysqli_fetch_assoc($memories)): ?><option value="<?= (int)$row['hafiza_id'] ?>"><?= htmlspecialchars($row['hafiza_ad']) ?></option><?php endwhile; ?></select></label></div><label>Ürün görseli<input type="file" name="img" accept="image/jpeg,image/png,image/webp" required><small>JPG, PNG veya WEBP · Maksimum 5 MB</small></label><label>Satıcı<input name="satici" placeholder="Alışveriş" required></label><label>Kategori<select name="kategori_id" required><option value="">Kategori seçin</option><?php while($row=mysqli_fetch_assoc($categories)): ?><option value="<?= (int)$row['kategori_id'] ?>"><?= htmlspecialchars($row['kategori_ad']) ?></option><?php endwhile; ?></select></label></section><section><h2>Fiyat ve stok</h2><div class="form-two"><label>Fiyat (TL)<input type="number" name="fiyat" min="1" step="0.01" placeholder="49999" required></label><label>Puan<input type="number" name="puan" min="0" max="5" step=".1" value="4.5" required></label></div><div class="form-two"><label>Stok durumu<select name="stok_id" required><option value="">Durum seçin</option><?php while($row=mysqli_fetch_assoc($stocks)): ?><option value="<?= (int)$row['stok_id'] ?>"><?= htmlspecialchars($row['stok_ad']) ?></option><?php endwhile; ?></select></label><label>Stok adedi<input type="number" name="stok_adet" min="0" value="10" required></label></div><label>Teslimat bilgisi<input name="teslimat" value="1-3 iş günü" required></label><label>Ödeme seçenekleri<input name="odeme_secenekleri" value="Kredi Kartı, Kapıda Ödeme" required></label><label>Taksit seçenekleri<input name="taksit_secenekleri" value="3 - 6 - 12 Ay'a Kadar" required></label></section></div><div class="admin-form-actions"><a href="telefon.php" class="admin-secondary">Vazgeç</a><button class="admin-primary" type="submit"><i class="bi bi-plus-lg"></i> Ürünü kaydet</button></div></form></div></main></div></body></html>
