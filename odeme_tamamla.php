<?php
include 'admin/baglanti.php';
checkAuth();

$userId = (int) $_SESSION['kullanici_id'];
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['sozlesme_checkbox'])) {
    header('Location: odeme.php');
    exit;
}

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS siparisler (
    siparis_id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    toplam_tutar DECIMAL(10,2) NOT NULL,
    odeme_secimi VARCHAR(40) NOT NULL,
    durum VARCHAR(30) NOT NULL DEFAULT 'Hazırlanıyor',
    olusturma_tarihi DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$payment = trim($_POST['odeme_secimi'] ?? 'taksitli');
$payment = in_array($payment, ['taksitli', 'aninda_havale'], true) ? $payment : 'taksitli';
$totalQuery = mysqli_query($conn, "SELECT COALESCE(SUM(t.fiyat * s.adet), 0) AS toplam FROM sepet s INNER JOIN telefon t ON t.id = s.telefon_id WHERE s.kullanici_id = $userId");
$total = (float) (mysqli_fetch_assoc($totalQuery)['toplam'] ?? 0);
if ($total <= 0) {
    header('Location: sepet.php');
    exit;
}

mysqli_begin_transaction($conn);
try {
    $stmt = mysqli_prepare($conn, "INSERT INTO siparisler (kullanici_id, toplam_tutar, odeme_secimi) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ids", $userId, $total, $payment);
    mysqli_stmt_execute($stmt);
    mysqli_query($conn, "DELETE FROM sepet WHERE kullanici_id = $userId");
    mysqli_commit($conn);
    header('Location: siparisler.php?success=1');
    exit;
} catch (Throwable $exception) {
    mysqli_rollback($conn);
    http_response_code(500);
    exit('Sipariş oluşturulamadı. Lütfen tekrar deneyin.');
}
