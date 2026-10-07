<header>
    <div class="logo">
        <a href="index.php">
            <img src="assets/img/logo_trim.png" alt="Alışveriş Projesi Logo" />
        </a>
    </div>
    <div class="title">
        <i class="bi bi-shield-lock"></i>
        <h5>Ödeme Sayfası</h5>
    </div>
    <div class="user">
        <i class="bi bi-person-circle"></i>
        <?php
        $kullanici_id = $_SESSION['kullanici_id'] ?? null;
        $kullanici_sorgu = mysqli_query($conn, "SELECT ad, soyad FROM kullanici WHERE kullanici_id = $kullanici_id");
        $kullanici_veri = mysqli_fetch_assoc($kullanici_sorgu);
        echo '<span>' . htmlspecialchars($kullanici_veri['ad'] . ' ' . $kullanici_veri['soyad']) . '</span>';
        ?>
    </div>
</header>