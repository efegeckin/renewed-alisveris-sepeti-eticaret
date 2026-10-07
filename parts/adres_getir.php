<?php
include '../admin/baglanti.php';


//sehre göre ilçeleri getir
if (isset($_POST['il_id'])) {
    $il_id = intval($_POST['il_id']);
    $ilceler = mysqli_query($conn, "SELECT * FROM ilceler WHERE il_id='$il_id'");
    $data = [];
    while ($row = mysqli_fetch_assoc($ilceler)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if (isset($_POST['ilce_id'])) {
    $ilce_id = intval($_POST['ilce_id']);
    $mahalleler = mysqli_query($conn, "SELECT * FROM mahalleler WHERE ilce_id='$ilce_id'");
    $data = [];
    while ($row = mysqli_fetch_assoc($mahalleler)) {
        $data[] = $row;
    }
    echo json_encode($data);
}
