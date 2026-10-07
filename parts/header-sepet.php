<div class="row top-bar w-100 mx-auto bg-dark">
    <div class="announcement w-75 col-12 mx-auto text-center text-white p-2">
        <p id="adminText" class="mb-0">Ücretsiz Kargo | Kapıda Ödeme | 7/24 Destek</p>
        <a id="admin" class="admin" href="admin/admin.php">
            <i class="bi bi-incognito"></i>
        </a>
    </div>
</div>
<div class="row bg-white w-100 mx-auto border-bottom">
    <div class="header w-100 col-12 mx-auto text-center text-white p-4 d-flex justify-content-between align-items-center ">
        <a class="logo mb-2" href="index.php">
            <img src="assets/img/logo_trim.png" alt="Alışveriş Projesi Logo">
        </a>
        <div class="input-group w-25">
            <span class="input-group-text" id="home">
                <a href="index.php">
                    <i style="text-decoration: none; color: black;" class="bi bi-house-door"></i>
                </a>
            </span>
            <input type="text" class="form-control w-25 mx-auto" placeholder="Ara..." aria-label="Ara..." aria-describedby="home">
        </div>

        <div class="bar d-flex justify-content-center align-items-center gap-4">


            <a href="sepet.php">
                <div class="sepetim p-3 d-flex justify-content-center align-items-center gap-3">
                    <i class="bi bi-box2-fill"></i>
                    Siparişlerim

                </div>
            </a>
            <div class="dropdown ">
                <button class="giris p-3 d-flex justify-content-center align-items-center gap-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    $kullanici_id = $_SESSION['kullanici_id'] ?? null;
                    $kullanici_sorgu = mysqli_query($conn, "SELECT img FROM kullanici WHERE kullanici_id = $kullanici_id");
                    $kullanici_veri = mysqli_fetch_assoc($kullanici_sorgu);
                    echo '<img class="profil-img rounded-circle" style="width: 2.5rem; border: 1px solid #c0c0c0ff;" src="assets/img/' . htmlspecialchars($kullanici_veri['img']) . '">';

                    ?>
                    <span style="font-size: 0.8rem; width:auto; margin-left: 0.2rem;">
                        <?php
                        //session dan ad ve soyad bilgisini al

                        $kullanici_id = $_SESSION['kullanici_id'] ?? null;
                        if ($kullanici_id) {
                            $kullanici_sorgu = mysqli_query($conn, "SELECT ad, soyad FROM kullanici WHERE kullanici_id = $kullanici_id");
                            $kullanici_veri = mysqli_fetch_assoc($kullanici_sorgu);
                            echo htmlspecialchars($kullanici_veri['ad'] . ' ' . $kullanici_veri['soyad']);
                        } else {
                            echo "Misafir Kullanıcı";
                        }



                        ?></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item text-danger" href="logout.php">Çıkış Yap</a></li>

                </ul>
            </div>

        </div>

    </div>
</div>
<div class="row bg-white p-2 w-100 mx-auto border-bottom">
    <div class="menu2 w-75 col-12 mx-auto d-flex justify-content-between align-items-center gap-4">
        <div class="d-flex justify-content-center align-items-center gap-1">
            <h3>Sepetim</h3>
            <?php
            // sepet tablosundaki veri sayısını al
            $kullanici_id = $_SESSION['kullanici_id'] ?? 0;
            $sepet_sorgu = mysqli_query($conn, "SELECT SUM(adet) AS toplam_adet FROM sepet WHERE kullanici_id = $kullanici_id");
            $sepet_veri = mysqli_fetch_assoc($sepet_sorgu);
            ?>
            <span><?php echo htmlspecialchars("(" . $sepet_veri['toplam_adet'] . " Ürün)"); ?></span>
        </div>
        <div>
            <a class="btn btn-outline-danger p2" onclick="return confirm('Sepeti temizlemek istediğinize emin misiniz?');" href="sepet_temizle.php">
                <i class="bi bi-trash3-fill"></i> Sepeti Temizle
            </a>

        </div>


    </div>
</div>
<script>
    const admin = document.getElementById('admin');
    const adminText = document.getElementById('adminText');
    admin.addEventListener('mouseenter', () => {
        adminText.style.transition = 'all 0.3s ease-in-out';
        adminText.innerHTML = 'Admin Paneli';


    });

    admin.addEventListener('mouseleave', () => {
        adminText.style.transition = 'all 0.3s ease-in-out';
        adminText.innerHTML = 'Ücretsiz Kargo | Kapıda Ödeme | 7/24 Destek';
    });
</script>