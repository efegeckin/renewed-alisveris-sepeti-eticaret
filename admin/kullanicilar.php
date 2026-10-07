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
                <div class="col-12">
                    <h3>Kullanıcılar</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kullanıcı ID</th>
                                <th>Ad</th>
                                <th>Soyad</th>
                                <th>E-Posta</th>
                                <th>Kullanıcı Adı</th>
                                <th>Kayıt Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $kullanicilar_sorgu = mysqli_query($conn, "SELECT kullanici_id, ad, soyad, eposta, username, rol FROM kullanici ORDER BY kullanici_id DESC");
                            while ($kullanici = mysqli_fetch_assoc($kullanicilar_sorgu)) {
                                echo "<tr>
                                        <td>" . htmlspecialchars($kullanici['kullanici_id']) . "</td>
                                        <td>" . htmlspecialchars($kullanici['ad']) . "</td>
                                        <td>" . htmlspecialchars($kullanici['soyad']) . "</td>
                                        <td>" . htmlspecialchars($kullanici['eposta']) . "</td>
                                        <td>" . htmlspecialchars($kullanici['username']) . "</td>
                                        <td>" . htmlspecialchars($kullanici['rol']) . "</td>
                                        <td>
                                            <a href='kullanici_duzenle.php?kullanici_id=" . urlencode($kullanici['kullanici_id']) . "' class='btn btn-sm btn-primary me-2'><i class='bi bi-pencil-square'></i></a>
                                            <a href='kullanici_sil.php?kullanici_id=" . urlencode($kullanici['kullanici_id']) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');\"><i class='bi bi-trash'></i></a>
                                        </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
        <script src="../assets/js/admin.js"></script>
</body>

</html>