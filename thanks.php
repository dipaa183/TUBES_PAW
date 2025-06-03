<?php
session_start();

if (empty($_SESSION['checkout_success'])) {
    header('Location: cart.php');
    exit;
}
unset($_SESSION['checkout_success']); // Hapus status setelah ditampilkan
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Terima Kasih</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container pt-5">
    <div class="alert alert-success">
        <h4 class="alert-heading">Terima Kasih!</h4>
        <p>Pesanan Anda telah berhasil dikirim. Kami akan memprosesnya secepat mungkin.</p>
        <hr>
        <a href="cart.php?checkout_success=1" class="btn btn-primary">Lihat Status Pemesanan</a>
        <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>
</body>

</html>