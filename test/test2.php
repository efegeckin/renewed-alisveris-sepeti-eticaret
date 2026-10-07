<?php

include "testbaglanti.php";

if ($_POST) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];
    $cinsiyet = $_POST['cinsiyet'];
    $sehir = $_POST['sehir'];
    $hobi = implode(", ", $_POST['hobi']);
    $aciklama = $_POST['aciklama'];

    $insert = mysqli_query($conn, "INSERT INTO test (ad, soyad, email, sifre, cinsiyet, sehir, hobi, aciklama) VALUES ('$ad', '$soyad', '$email', '$sifre', '$cinsiyet', '$sehir', '$hobi', '$aciklama')");

    if ($insert) {
        $error = "<div class='alert alert-success'>Kayıt Başarılı</div>";
        header("Location: test2.php?success=1");
        exit;
    } else {
        $error = "<div class='alert alert-danger'>Kayıt Başarısız</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
<?php include '../parts/head.php'; ?>

<style>
    body{
        padding: 100px;
        background-color: brown;
    }
    label{
        background-color: white;
        padding: 5px;
        border-radius: 5px;
    }
   form{
        background-color: #ddddddff;
        padding: 20px;
        border-radius: 10px;
        margin-top: 50px;
    }
    form span{
        margin:5px;
        background-color: white;
        padding: 5px;
        border-radius: 5px;
    }
    input, select, textarea{
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
</head>
<body>
    <?php if(isset($error)) echo $error; ?>
    <table class="table w-100 align-middle vertical-center text-center table-striped table-dark table-hover table-borderless">
        <tr>
            <td>Ad</td>
            <td>Soyad</td>
            <td>E-Posta</td>
            <td>Şifre</td>
            <td>Cinsiyet</td>
            <td>Sehir</td>
            <td>Hobi</td>
            <td class="w-25">Açıklama</td>

        </tr>
        <?php
        $tablo = mysqli_query($conn, "SELECT * FROM test ORDER BY id DESC");
        while ($k = mysqli_fetch_assoc($tablo)) {
            echo "<tr>
                    <td>{$k['ad']}</td>
                    <td>{$k['soyad']}</td>
                    <td>{$k['email']}</td>
                    <td>{$k['sifre']}</td>
                    <td>{$k['cinsiyet']}</td>
                    <td>{$k['sehir']}</td>
                    <td>{$k['hobi']}</td>
                    <td style='max-width: 200px; word-wrap: break-word;'>{$k['aciklama']}</td>
                </tr>";
        }
        ?>
    </table>
    
    <form class="w-50 mx-auto" action="test2.php" method="post">
        
        <input name="ad" type="text" class="form-control" placeholder="Adınız" required>   

        <input name="soyad" type="text" class="form-control" placeholder="Soyadınız" required>   
            
        <input name="email" type="email" class="form-control" placeholder="E-Postanız" required>

        <input name="sifre" type="password" class="form-control" placeholder="Parola" required>

        <div class="mb-3">
            Şehir Seçiniz:
            <select class="form-select w-25" name="sehir" id="">
                <option value="34">İstanbul</option>
                <option value="14">Bolu</option>
                <option value="06">Ankara</option>
            </select>
        </div>
        
        <div class="mb-3">

            Cinsiyet Seçiniz:          
            <input type="radio" name="cinsiyet" value="erkek"><span>Erkek</span>           
            <input type="radio" name="cinsiyet" value="kadin"><span>Kadın</span>
        </div>
        <div class="mb-3">

            Hobi Seçiniz:
            <input type="checkbox" name="hobi[]" value="müzik"><span>Müzik</span>
            <input type="checkbox" name="hobi[]" value="sinema"><span>Sinema</span>
            <input type="checkbox" name="hobi[]" value="spor"><span>Spor</span>
            <input type="checkbox" name="hobi[]" value="kitap"><span>Kitap Okumak</span>
        </div>
        <div class="mb-3">
            <textarea  name="aciklama" class="form-control" placeholder="Açıklama" rows="3" required></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Gönder</button>
    </form>

</body>
</html>