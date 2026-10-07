<?php
include 'admin/baglanti.php';
//seçilen sepet kalemini veritabanından sil
//silmeden önce sor
checkAuth();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Sil</title>
    <?php include 'parts/head.php'; ?>
</head>

<body style='font-family:Arial; text-align:center; margin-top:100px;'>
    <?php

    if (isset($_GET['sepet_kalem_id'])) {
        $sepet_kalem_id = intval($_GET['sepet_kalem_id']);
        // Eğer onay gelmediyse, kullanıcıya sor
        if (!isset($_GET['confirm'])) { ?>
            <h2>Ürünü sepetten silmek istediğinize emin misiniz?</h2>
            <a href='sepet_sil.php?sepet_kalem_id=<?php echo $sepet_kalem_id; ?>&confirm=1' class="btn btn-danger">Evet, Sil</a>
            <a href='sepet.php' class='btn btn-success'>Hayır, Vazgeç</a>

    <?php
            exit;
        } else {
            // Onaylandıysa sil
                $kullanici_id = (int) $_SESSION['kullanici_id'];
                mysqli_query($conn, "DELETE FROM sepet WHERE sepet_kalem_id=$sepet_kalem_id AND kullanici_id=$kullanici_id");
            header('Location: sepet.php');
            exit;
        }
    }
    ?>

</body>

</html>

<?php
header('Location: sepet.php');
exit;
?>