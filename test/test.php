<?php

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $adsoyad = $_POST["adsoyad"];
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];
    $sifre2 = $_POST["sifre2"];
    if($_POST['sehir'] == -1){
        $error = "<p>Lütfen bir şehir seçiniz.</p>";
        $sehir = "";
    }
    else{
        $sehir = $_POST["sehir"];
    }


    if(empty($_POST['cinsiyet'])){
        $error = "<p>Lütfen cinsiyetinizi belirtiniz.</p>";
        $cinsiyet = "";
    }
    else{
        $cinsiyet = $_POST["cinsiyet"];
    }

    if(empty($_POST['hobi'])){
        $error = "<p>Lütfen en az bir hobi seçiniz.</p>";
        $hobi = array();
    }
    else{
        $hobi = $_POST["hobi"];
    }

    $aciklama = $_POST["aciklama"];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<?php include 'parts/head.php'; ?>

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
    p{
        color: red;
        font-size: 18px;
        background-color: white;
        width: fit-content;
        padding: 10px 20px;
    }
</style>
</head>
<body>
    <?php if(isset($error)) echo $error; ?>
    <table class="table w-100 align-middle vertical-center text-center table-striped table-dark table-hover table-borderless">
        <tr>
            <td>Ad Soyad</td>
            <td>E-Posta</td>
            <td>Parola</td>
            <td>Parola 2</td>
            <td>Şehir</td>
            <td>Cinsiyet</td>
            <td>Hobiler</td>
            <td>Açıklama</td>
        </tr>
        <tr>
            <td><?php echo $adsoyad; ?></td>
            <td><?php echo $email; ?></td>
            <td><?php echo $sifre; ?></td>
            <td><?php echo $sifre2; ?></td>
            <td><?php echo $sehir; ?></td>
            <td><?php echo $cinsiyet; ?></td>
            <td><?php foreach ($hobi as $hobiler) {
                        echo $hobiler;echo "<br>";
                        }; ?></td>
            <td><?php echo $aciklama; ?></td>
            
        </tr>
    </table>
    
    <form class="w-50 mx-auto" action="test.php" method="post">
        <div class="mb-3">
            <label for="adsoyad" class="form-label">Ad Soyad</label>
            <input name="adsoyad" type="text" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input name="email" type="email" class="form-control" id="email" required>
        </div>
        <div class="mb-3">
            <label for="sifre" class="form-label">Parola</label>
            <input name="sifre" type="password" class="form-control" id="parola" required>
        </div>
        <div class="mb-3">
            <label for="sifre2" class="form-label">Parola 2</label>
            <input name="sifre2" type="password" class="form-control" id="parolaTwo" required>
        </div>
        <div class="mb-3">
            <label for="sehir" class="form-label">Şehir</label><br>
            <select name="sehir" id="">
                <option value="-1">Seçiniz</option>
                <option value="34">İstanbul</option>
                <option value="14">Bolu</option>
                <option value="06">Ankara</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="cinsiyet" class="form-label">Cinsiyet</label><br>

            Erkek
            <input type="radio" name="cinsiyet" value="erkek">

            Kadın
            <input type="radio" name="cinsiyet" value="kadin">
        </div>
        <div class="mb-3">
            <label for="hobi" class="form-label">Hobi</label><br>

            <input type="checkbox" name="hobi[]" value="sinema"> Sinema
            <input type="checkbox" name="hobi[]" value="spor"> Fitness
            <input type="checkbox" name="hobi[]" value="kitap"> Kitap Okumak
        </div>
        <div class="mb-3">
            <label for="aciklama" class="form-label">Açıklama</label>
            <textarea  name="aciklama" class="form-control" id="aciklama" rows="3" required></textarea>
        </div>
        <button id="btn" class="btn btn-primary" type="submit">Gönder</button>
    </form>


    <script>
        const table = document.querySelector('table');
        const btn = document.getElementById('btn');

        btn.addEventListener('click', () => {
                table.style.display = "block";

        })
    </script>
</body>
</html>