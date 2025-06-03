<?php
session_start();

// Cek jika user belum melakukan checkout
if (empty($_SESSION['last_checkout'])) {
    header('Location: cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proses pembayaran di sini (misal: simpan metode pembayaran ke database jika perlu)
    // Setelah pembayaran, redirect ke halaman terima kasih
    header('Location: thanks.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
</head>
<body>
    <h2>Pilih Metode Pembayaran</h2>
    <form method="post">
        <label>
            <input type="radio" name="metode_pembayaran" value="transfer_bank" required>
            Transfer Bank
        </label><br>
        <label>
            <input type="radio" name="metode_pembayaran" value="cod" required>
            Bayar di Tempat (COD)
        </label><br>
        <label>
            <input type="radio" name="metode_pembayaran" value="ewallet" required>
            E-Wallet
        </label><br><br>
        <button type="submit">Bayar</button>
    </form>
</body>
</html>