<?php
include 'admin/baglanti.php';
session_start();

$featured = mysqli_query(
    $conn,
    "SELECT t.*, m.model_ad, h.hafiza_ad
     FROM telefon t
     LEFT JOIN model m ON m.model_id = t.model_id
     LEFT JOIN hafiza h ON h.hafiza_id = t.hafiza_id
     WHERE t.stok_id <> 2
     ORDER BY t.puan DESC, t.id DESC
     LIMIT 4"
);
$latest = mysqli_query(
    $conn,
    "SELECT t.*, m.model_ad, h.hafiza_ad
     FROM telefon t
     LEFT JOIN model m ON m.model_id = t.model_id
     LEFT JOIN hafiza h ON h.hafiza_id = t.hafiza_id
     ORDER BY t.id DESC
     LIMIT 4"
);

function productCard(array $product): string
{
    $name = htmlspecialchars(trim(($product['model_ad'] ?? 'Ürün') . ' ' . ($product['hafiza_ad'] ?? '')));
    $image = htmlspecialchars($product['img'] ?? '');
    $price = number_format((float) $product['fiyat'], 0, ',', '.');
    $rating = min(5, max(0, (float) $product['puan']));
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= round($rating) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
    }

    return '<article class="product-card">
        <a class="product-card-link" href="detay.php?id=' . (int) $product['id'] . '">
            <div class="product-image-wrap">
                <span class="product-badge">Çok satan</span>
                <img src="assets/img/' . $image . '" alt="' . $name . '" loading="lazy">
                <span class="product-quick"><i class="bi bi-arrow-up-right"></i></span>
            </div>
            <div class="product-content">
                <div class="product-rating">' . $stars . ' <span>' . number_format($rating, 1, ',', '.') . '</span></div>
                <h3>' . $name . '</h3>
                <p class="product-meta">Alışveriş güvencesi · Hızlı teslimat</p>
                <strong class="product-price">' . $price . ' TL</strong>
            </div>
        </a>
    </article>';
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <?php include 'parts/head.php'; ?>
    <meta name="description" content="Alışveriş: teknoloji tutkunları için seçilmiş akıllı telefonlar ve fırsatlar.">
</head>
<body>
    <?php include 'parts/header.php'; ?>

    <main>
        <section class="hero-shell">
            <div class="hero-copy">
                <span class="eyebrow"><i class="bi bi-stars"></i> Yeni sezon teknoloji</span>
                <h1>Teknolojiye<br><em>yeni bir açı.</em></h1>
                <p>Günlük hayatını kolaylaştıran, sana iyi hissettiren cihazları keşfet.</p>
                <div class="hero-actions">
                    <a class="btn btn-brand" href="telefon.php">Koleksiyonu keşfet <i class="bi bi-arrow-up-right"></i></a>
                    <a class="hero-link" href="#one-cikanlar">Öne çıkanlara bak <i class="bi bi-arrow-down"></i></a>
                </div>
                <div class="hero-proof"><span><i class="bi bi-check2-circle"></i> Aynı gün kargo</span><span><i class="bi bi-shield-check"></i> Güvenli alışveriş</span></div>
            </div>
            <div id="homeHero" class="carousel slide hero-media" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/img/slider_1.jpg" alt="Yeni nesil akıllı telefonlar">
                        <div class="hero-slide-content"><span>01 / 03</span><h2>Yeni nesil,<br><em>senin ritmin.</em></h2><p>Günün her anına eşlik eden seçili telefonları keşfet.</p><a href="telefon.php" class="hero-slide-link">Koleksiyonu gör <i class="bi bi-arrow-up-right"></i></a></div>
                    </div>
                    <div class="carousel-item">
                        <img src="assets/img/slider_2.jpg" alt="Avantajlı teknoloji fırsatları">
                        <div class="hero-slide-content"><span>02 / 03</span><h2>Akıllı seçim,<br><em>iyi fiyat.</em></h2><p>İhtiyacın olan teknolojiyi avantajlı fiyatlarla bul.</p><a href="telefon.php?sort=campaign" class="hero-slide-link">Fırsatları incele <i class="bi bi-arrow-up-right"></i></a></div>
                    </div>
                    <div class="carousel-item">
                        <img src="assets/img/slider_3.jpg" alt="Tablet ve teknoloji ürünleri">
                        <div class="hero-slide-content"><span>03 / 03</span><h2>Ekranı büyüt,<br><em>hayatı keşfet.</em></h2><p>Çalışma ve eğlence için güçlü teknoloji seçkisi.</p><a href="telefon.php" class="hero-slide-link">Ürünleri keşfet <i class="bi bi-arrow-up-right"></i></a></div>
                    </div>
                </div>
                <button class="carousel-control-prev hero-control" type="button" data-bs-target="#homeHero" data-bs-slide="prev" aria-label="Önceki slayt"><span class="hero-arrow"><i class="bi bi-arrow-left"></i></span></button>
                <button class="carousel-control-next hero-control" type="button" data-bs-target="#homeHero" data-bs-slide="next" aria-label="Sonraki slayt"><span class="hero-arrow"><i class="bi bi-arrow-right"></i></span></button>
                <div class="hero-dots" role="tablist" aria-label="Slider slaytları">
                    <button type="button" data-bs-target="#homeHero" data-bs-slide-to="0" class="active" aria-label="1. slayt"></button>
                    <button type="button" data-bs-target="#homeHero" data-bs-slide-to="1" aria-label="2. slayt"></button>
                    <button type="button" data-bs-target="#homeHero" data-bs-slide-to="2" aria-label="3. slayt"></button>
                </div>
                <div class="hero-progress" aria-hidden="true"><span></span></div>
            </div>
        </section>

        <section class="service-strip">
            <div><i class="bi bi-truck"></i><span><b>Hızlı teslimat</b><small>1-3 iş gününde kapında</small></span></div>
            <div><i class="bi bi-credit-card-2-front"></i><span><b>Esnek ödeme</b><small>12 aya varan taksit seçenekleri</small></span></div>
            <div><i class="bi bi-headset"></i><span><b>Yanındayız</b><small>7/24 destek ekibimiz hazır</small></span></div>
            <div><i class="bi bi-arrow-repeat"></i><span><b>Kolay iade</b><small>14 gün içinde sorunsuz iade</small></span></div>
        </section>

        <section class="section-block" id="one-cikanlar">
            <div class="section-heading">
                <div><span class="eyebrow">Senin için seçtik</span><h2>Öne çıkanlar</h2></div>
                <a href="telefon.php">Tüm ürünleri gör <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <div class="product-grid">
                <?php while ($product = mysqli_fetch_assoc($featured)): echo productCard($product); endwhile; ?>
            </div>
        </section>

        <section class="editorial-banner">
            <div><span class="eyebrow">Alışveriş seçkisi</span><h2>Yeni bir cihaz,<br>yeni bir başlangıç.</h2><a href="telefon.php" class="btn btn-light">Fırsatları incele <i class="bi bi-arrow-up-right"></i></a></div>
            <div class="editorial-stat"><strong>10+</strong><span>özenle seçilmiş<br>teknoloji ürünü</span></div>
        </section>

        <section class="section-block latest-section">
            <div class="section-heading"><div><span class="eyebrow">Son eklenenler</span><h2>Yeni keşifler</h2></div><a href="telefon.php">Mağazaya git <i class="bi bi-arrow-up-right"></i></a></div>
            <div class="product-grid"><?php while ($product = mysqli_fetch_assoc($latest)): echo productCard($product); endwhile; ?></div>
        </section>
    </main>
    <?php include 'parts/footer.php'; ?>
    <script>
        const homeHero = document.getElementById('homeHero');
        if (homeHero) {
            homeHero.addEventListener('slid.bs.carousel', (event) => {
                const progress = homeHero.querySelector('.hero-progress span');
                if (progress) progress.style.width = `${((event.to + 1) / 3) * 100}%`;
            });
        }
    </script>
</body>
</html>
