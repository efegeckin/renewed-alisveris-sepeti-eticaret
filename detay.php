<?php
include 'admin/baglanti.php';
session_start();

$success = "";
$error = "";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // İlk sorgu ile başlığı al
    $ilk_sorgu = mysqli_query($conn, "SELECT model_id FROM telefon WHERE id = $id");
    if (mysqli_num_rows($ilk_sorgu) > 0) {
        $row = mysqli_fetch_assoc($ilk_sorgu);
        $baslik = mysqli_real_escape_string($conn, $row['model_id']);
    } else {
        echo "Telefon bulunamadı.";
        exit;
    }
    $telefon_sorgu = null;
    $hafiza_id = isset($_GET['hafiza_id']) ? intval($_GET['hafiza_id']) : null;
    $renk_id = isset($_GET['renk_id']) ? intval($_GET['renk_id']) : null;
    $model_id = isset($_GET['model_id']) ? intval($_GET['model_id']) : null;
    if ($hafiza_id && $renk_id && $model_id) {
        $telefon_sorgu = mysqli_query($conn, "SELECT * FROM telefon WHERE model_id = '$baslik' AND hafiza_id = $hafiza_id AND renk_id = $renk_id AND model_id = $model_id");
    } elseif ($hafiza_id) {
        $telefon_sorgu = mysqli_query($conn, "SELECT * FROM telefon WHERE model_id = '$baslik' AND hafiza_id = $hafiza_id");
    } else if ($model_id) {
        $telefon_sorgu = mysqli_query($conn, "SELECT * FROM telefon WHERE model_id = '$baslik' AND model_id = $model_id");
    } elseif ($renk_id) {
        $telefon_sorgu = mysqli_query($conn, "SELECT * FROM telefon WHERE model_id = '$baslik' AND renk_id = $renk_id");
    } else {
        $telefon_sorgu = mysqli_query($conn, "SELECT * FROM telefon WHERE id = $id");
    }
    if (mysqli_num_rows($telefon_sorgu) > 0) {
        $telefon = mysqli_fetch_assoc($telefon_sorgu);
    } else {
        echo "Telefon bulunamadı.";
        exit;
    }
} else {
    echo "Geçersiz telefon ID'si.";
    exit;
}

//Sepete ekle buttonuna tıklanınca sepet tablosunu telefon id'si ve kullanıcı id'si ile doldur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['kullanici_id'])) {
        header('Location: login.php');
        exit;
    }
    $kullanici_id = (int) $_SESSION['kullanici_id'];
    $telefon_id = $telefon['id'];
    if ((int) $telefon['stok_id'] === 2 || (int) $telefon['stok_adet'] === 0) {
        $error = "Bu ürün şu anda stokta yok.";
    } else {

        // Önce sepette var mı kontrol et
        $sepet_kontrol_sorgu = mysqli_query($conn, "SELECT sepet_kalem_id FROM sepet WHERE kullanici_id=$kullanici_id AND telefon_id=" . (int) $telefon_id);
        if (mysqli_num_rows($sepet_kontrol_sorgu) > 0) {

            $sepet_guncelle_sorgu = "UPDATE sepet SET adet = LEAST(adet + 1, " . (int) $telefon['stok_adet'] . ") WHERE kullanici_id=$kullanici_id AND telefon_id=" . (int) $telefon_id;
            if (mysqli_query($conn, $sepet_guncelle_sorgu)) {
                $success = "Ürün sepette, adet artırıldı.";
            } else {
                $error = "Sepeti güncellerken bir hata oluştu.";
            }
        } else {
            $sepet_ekle_sorgu = "INSERT INTO sepet (kullanici_id, telefon_id, adet) VALUES ($kullanici_id, " . (int) $telefon_id . ", 1)";
            if (mysqli_query($conn, $sepet_ekle_sorgu)) {
                $success = "Ürün sepete eklendi.";
            } else {
                $error = "Sepete eklerken bir hata oluştu.";
            }
        }
    }
}




$hafiza_sorgu = mysqli_query($conn, "SELECT hafiza_ad FROM hafiza WHERE hafiza_id = " . intval($telefon['hafiza_id']));
$hafiza = mysqli_fetch_assoc($hafiza_sorgu);

$model_sorgu = mysqli_query($conn, "SELECT model_ad FROM model WHERE model_id = " . intval($telefon['model_id']));
$model = mysqli_fetch_assoc($model_sorgu);

$renk_sorgu = mysqli_query($conn, "SELECT renk_ad FROM renkler WHERE renk_id = " . intval($telefon['renk_id']));
$renk = mysqli_fetch_assoc($renk_sorgu);

$stok_sorgu = mysqli_query($conn, "SELECT stok_ad FROM stok_durumu WHERE stok_id = " . intval($telefon['stok_id']));
$stok = mysqli_fetch_assoc($stok_sorgu);

?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <?php include 'parts/head.php'; ?>
    <style>
        .card-image {
            transition: all 0.3s ease;
        }

        .card-image:hover {
            transform: scale(1.02);
        }

        .puan {
            font-size: 0.8rem;
            margin-right: 5px;
            background-color: #f5f5f5;
            padding: 2px 5px;
            border-radius: 5px;
            color: #333;
        }

        .satici {
            font-size: 0.9rem;
            color: #555;
            margin-top: 10px;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .odeme {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 0.9rem;
            color: #444;
            background-color: #f5f5f5;
            width: fit-content;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .odeme p {
            margin: 0;
        }

        .hafiza {
            font-size: 0.9rem;
            background-color: #f5f5f5;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px solid #000;
            color: #000;

        }

        .hafizaSelected {
            font-size: 0.9rem;
            background-color: #003177;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px solid #f5f5f5;
            color: #f5f5f5;

        }

        .renk {
            font-size: 0.9rem;
            background-color: #f5f5f5;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
            border: 1px solid #000;
            color: #000;

        }

        .renkSelected {
            font-size: 0.9rem;
            border: 1px solid transparent;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
            color: #f5f5f5;

        }

        .stok p {
            font-size: 0.9rem;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
            background-color: green;
            color: #fff;
        }

        .sepete-ekle {
            color: white;
            background-color: #003177;
            width: 80%;
            padding: 10px;
            border: none;
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            transition: all .3s ease;
        }

        .favoriler {
            color: white;
            background-color: #003177;
            padding: 10px;
            border: none;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            transition: all .3s ease;
        }

        .sepete-ekle:hover,
        .favoriler:hover {
            background-color: #0049afff;
        }

        .favori-animate {
            transition: .4s;
        }

        .hafiza {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hafiza:hover {
            background-color: #003177;
            color: #f5f5f5;
        }

        .renk {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .renk:hover {
            background-color: #003177;
            color: #f5f5f5;
        }

        p,
        button {
            margin: 0 !important;
        }

        .content {
            position: relative;
        }

        .alert {
            position: fixed;
            top: 5rem;
            right: 0;
            z-index: 1000;
            margin: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <?php include 'parts/header.php'; ?>

        <div class="content detail-page w-75 h-100 d-flex flex-column justify-content-start align-items-start mx-auto">
            <div class="row w-100 h-100">
                <div class="menuTree my-4">
                    <a style="text-decoration: none; color: black;" href="index.php"><i class="bi bi-house-door fs-6"></i></a> &gt; <a style="text-decoration: none; color: black;" href="telefon.php">Akıllı Telefon</a> &gt; <span><?php echo htmlspecialchars($model['model_ad']); ?></span>
                </div>


            </div>
            <div class="row mb-5 w-100 h-100 d-flex">
                <div class="col p-2 card w-50">
                    <img class=" card-image w-100" src="assets/img/<?php echo htmlspecialchars($telefon['img']) . "\" alt=\"" .  htmlspecialchars($model['model_ad']); ?>">
                </div>
                <div class="col w-50 p-2 mx-4">

                    <h2 style="font-size:28px"><?php echo htmlspecialchars($model['model_ad'] . " " . $hafiza['hafiza_ad'] . " " . $renk['renk_ad']); ?></h2>

                    <?php

                    echo "<div class='star-rating'>";
                    echo "<span class=\"puan\">" . htmlspecialchars($telefon['puan']) . " </span>";
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $telefon['puan']) {
                            echo "<i class=\"bi bi-star-fill checked\"></i>";
                        } else {
                            echo "<i class=\"bi bi-star\"></i>";
                        }
                    }
                    echo "</div>";

                    ?>

                    <p class="satici border my-3">Satıcı : <b style="color: #003177;"><?php echo htmlspecialchars($telefon['satici']); ?></b></p>
                    <h3 id="fiyat" style=" margin-top:10px;"><?php echo htmlspecialchars($telefon['fiyat']); ?> TL</h3>
                    <div class="odeme">
                        <p><?php echo htmlspecialchars($telefon['odeme_secenekleri']); ?></p>
                        <p style="color:#003177;"><?php echo htmlspecialchars($telefon['taksit_secenekleri']); ?></p>
                    </div>
                    <br>
                    <p class="mb-2"><b>Hafıza :</b> <?php echo htmlspecialchars($hafiza['hafiza_ad']); ?></p>
                    <div class="d-flex gap-2">

                        <?php
                        $selected_hafiza_id = isset($_GET['hafiza_id']) ? intval($_GET['hafiza_id']) : (isset($telefon['hafiza_id']) ? intval($telefon['hafiza_id']) : null);
                        // Aynı başlığa bağlı hafızaları çek
                        $mdl = $telefon['model_id'];
                        $hafiza_list = mysqli_query($conn, "SELECT h.* FROM hafiza h INNER JOIN telefon t ON h.hafiza_id = t.hafiza_id WHERE t.model_id = '" . mysqli_real_escape_string($conn, $mdl) . "' GROUP BY h.hafiza_id ORDER BY h.hafiza_id DESC");
                        while ($h = mysqli_fetch_assoc($hafiza_list)) {
                            $class = ($selected_hafiza_id === intval($h['hafiza_id'])) ? 'hafizaSelected' : 'hafiza';
                            $url = "detay.php?id=" . urlencode($id) . "&hafiza_id=" . urlencode($h['hafiza_id']);
                            echo "<a href='$url' class='$class hafiza mb-3' style='text-decoration:none;display:inline-block;'>{$h['hafiza_ad']}</a>";
                        }
                        ?>

                    </div>
                    <p class="mb-2"><b>Renk :</b> <?php echo htmlspecialchars($renk['renk_ad']); ?></p>
                    <div id="renk" class="d-flex gap-2">

                        <?php
                        $selected_renk_id = isset($_GET['renk_id']) ? intval($_GET['renk_id']) : (isset($telefon['renk_id']) ? intval($telefon['renk_id']) : null);
                        // Aynı başlığa bağlı renkleri çek
                        $mdl = $telefon['model_id'];
                        $renk_list = mysqli_query($conn, "SELECT r.* FROM renkler r INNER JOIN telefon t ON r.renk_id = t.renk_id WHERE t.model_id = '" . mysqli_real_escape_string($conn, $mdl) . "' GROUP BY r.renk_id ORDER BY r.renk_id DESC");
                        while ($r = mysqli_fetch_assoc($renk_list)) {
                            $class = ($selected_renk_id === intval($r['renk_id'])) ? 'renkSelected' : 'renk';
                            $url = "detay.php?id=" . urlencode($id);
                            if (isset($telefon['hafiza_id'])) {
                                $url .= "&hafiza_id=" . urlencode($telefon['hafiza_id']);
                            }
                            $url .= "&model_id=" . urlencode($telefon['model_id']);
                            $url .= "&renk_id=" . urlencode($r['renk_id']);
                            echo "<a href='$url' class='$class renk mb-2' style='text-decoration:none;display:inline-block;'>{$r['renk_ad']}</a>";
                        }
                        ?>

                    </div>

                    <div class="d-flex mt-4">
                        <!-- Sepete ekle butonu -->
                        <form class="d-flex w-50" action="" method="POST" id="sepeteEkleForm">
                            <button class="sepete-ekle w-100" type="submit" form="sepeteEkleForm">
                                Sepete Ekle

                            </button>
                            <button type="button" id="favoriBtn" class="favoriler"><i class="bi bi-bookmark"></i></button>
                        </form>


                    </div>
                    <div class="stok d-flex gap-2 mt-3">

                        <p id="stokDurumu">Stok Durumu : <?php echo htmlspecialchars($stok['stok_ad']); ?></p>

                    </div>

                </div>


            </div>
            <?php if (!empty($success)) : ?>
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <strong><?php echo htmlspecialchars($success); ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo htmlspecialchars($error); ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>


        <?php include "parts/footer.php"; ?>
        <script>
            const favoriBtn = document.getElementById("favoriBtn");
            var degis = true;
            favoriBtn.addEventListener('click', () => {

                if (degis === true) {
                    favoriBtn.classList.add('favori-animate');
                    favoriBtn.innerHTML = '<i class="bi bi-bookmark-fill"></i>';
                    degis = false;
                } else if (degis === false) {
                    favoriBtn.innerHTML = '<i class="bi bi-bookmark"></i>';
                    degis = true;
                }
            });

            const hafiza = document.getElementById('hafiza');



            const renk = document.getElementById('renk');
            const renkClass = document.querySelectorAll('#renk p');
            const renkSelected = document.querySelectorAll('#renk .renkSelected');

            //renkselected hangi renkteyse o rengin textine göre background rengini ayarla

            if (renkSelected.length > 0) {
                const selectedText = renkSelected[0].innerText.toLowerCase();
                let bgColor = '#f5f5f5'; // Varsayılan renk

                switch (selectedText) {
                    case 'kırmızı':
                        bgColor = 'red';
                        break;
                    case 'mavi':
                        bgColor = 'blue';
                        break;
                    case 'beyaz':
                        bgColor = 'grey';
                        break;
                    case 'siyah':
                        bgColor = 'black';
                        break;
                    case 'turuncu':
                        bgColor = 'orangered';
                        break;
                        // Diğer renkler için eklemeler yapabilirsiniz
                    default:
                        bgColor = '#f5f5f5';
                }

                renkSelected[0].style.backgroundColor = bgColor;
                renkSelected[0].style.color = (bgColor === 'black' || bgColor === 'blue' || bgColor === 'grey' || bgColor === 'red' || bgColor === 'orangered') ? 'white' : 'black';
            }

            const stokDurumu = document.getElementById('stokDurumu').innerText;
            const sepetBtn = document.querySelector('.sepete-ekle');
            const fiyat = document.getElementById('fiyat');
            if (stokDurumu.includes('Tükendi')) {
                document.getElementById('stokDurumu').style.backgroundColor = 'red';
                sepetBtn.style.display = 'none';
                favoriBtn.style.display = 'none';
                fiyat.style.textDecoration = 'line-through';

            } else if (stokDurumu.includes('Mevcut')) {
                document.getElementById('stokDurumu').style.backgroundColor = 'green';
            } else if (stokDurumu.includes('Ön Sipariş')) {
                document.getElementById('stokDurumu').style.backgroundColor = 'orange';
            }
        </script>
        <script src="assets/js/app.js"></script>
</body>

</html>