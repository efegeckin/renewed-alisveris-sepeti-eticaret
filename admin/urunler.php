<?php
session_start();
include 'baglanti.php';

checkAdmin();


?>
<!DOCTYPE html>
<html lang="tr">
<head>
<?php include 'a-parts/head.php'; ?>

</head>
<body>
    <div class="container-fluid d-flex w-100 h-100 p-0">
        <?php include 'a-parts/header.php'; ?>       
        <div class="content d-flex flex-column justify-content-start align-items-start">
            <?php include 'a-parts/content_top.php'; ?>
            <div class="row w-100 p-4">
            <?php
            $kategoriler1 = mysqli_query($conn, "SELECT kategori_ad FROM kategori WHERE kategori_id = 1");
            $kategoriler2 = mysqli_query($conn, "SELECT kategori_ad FROM kategori WHERE kategori_id = 2");
            $kategoriler3 = mysqli_query($conn, "SELECT kategori_ad FROM kategori WHERE kategori_id = 3");
            $kategori1 = mysqli_fetch_assoc($kategoriler1);
            $kategori2 = mysqli_fetch_assoc($kategoriler2);
            $kategori3 = mysqli_fetch_assoc($kategoriler3);

            ?>
                <div class="col">
                     <div class="btn-group dropdown urunler">
                        <button type="button" class="w-100 py-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-phone"></i> <?php echo htmlspecialchars($kategori1['kategori_ad'])?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="w-100 py-2" href="telefon.php"><i class="bi bi-list-ul"></i> Liste</a></li>
                            <li><a class="w-100 py-2" href="telefon_ekle.php"><i class="bi bi-plus"></i> Ekle</a></li>
                        </ul>
                    </div>   
                </div>
                <div class="col">
                    <div class="btn-group dropdown urunler">
                        <button type="button" class="w-100 py-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-tablet-landscape"></i> <?php echo htmlspecialchars($kategori2['kategori_ad'])?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="w-100 py-2" href="telefon.php"><i class="bi bi-list-ul"></i> Liste</a></li>
                            <li><a class="w-100 py-2" href="telefon_ekle.php"><i class="bi bi-plus"></i> Ekle</a></li>
                        </ul>
                    </div>   
                </div>
                <div class="col">
                    <div class="btn-group dropdown urunler">
                        <button type="button" class="w-100 py-3" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-laptop"></i> <?php echo htmlspecialchars($kategori3['kategori_ad'])?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="w-100 py-2" href="telefon.php"><i class="bi bi-list-ul"></i> Liste</a></li>
                            <li><a class="w-100 py-2" href="telefon_ekle.php"><i class="bi bi-plus"></i> Ekle</a></li>
                        </ul>
                    </div>   
                </div>

                <!-- Yönetici paneli içeriği buraya gelecek -->
            </div>
        </div>
        

    </div>
<script src="../assets/js/admin.js"></script>
</body>
</html>