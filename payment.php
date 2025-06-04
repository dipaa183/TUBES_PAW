<?php
session_start();

// Optional: Cek jika user belum checkout, bisa redirect ke cart
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simpan metode pembayaran jika perlu
    $_SESSION['metode_pembayaran'] = $_POST['metode_pembayaran'] ?? '';

    // Setelah pembayaran, redirect ke halaman terima kasih
    header('Location: thanks.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <h2>Pilih Metode Pembayaran</h2>
    <form method="post">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="metode_pembayaran" id="bank" value="Transfer Bank" required>
            <label class="form-check-label" for="bank">Transfer Bank</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="COD" required>
            <label class="form-check-label" for="cod">Bayar di Tempat (COD)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="metode_pembayaran" id="ewallet" value="E-Wallet" required>
            <label class="form-check-label" for="ewallet">E-Wallet</label>
        </div>
        <button type="submit" class="btn btn-success mt-4">Bayar</button>
    </form>
</div>
</body>
</html>