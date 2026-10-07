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

                <h3 class="m-0">Hoşgeldiniz, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h3>

                <!-- Yönetici paneli içeriği buraya gelecek -->
            </div>
        </div>
        

    </div>
<script src="../assets/js/admin.js"></script>
</body>
</html>