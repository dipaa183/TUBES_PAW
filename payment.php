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

    // Kosongkan cart (selesaikan pesanan)
    unset($_SESSION['cart']);

    // Redirect ke halaman ini dengan parameter sukses
    header('Location: payment.php?success=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php if (isset($_GET['success'])): ?>
    <script>
        window.onload = function() {
            alert('Pembayaran berhasil! Terima kasih telah memesan layanan kami.');
            window.location.href = 'index.php';
        }
    </script>
    <?php endif; ?>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h3 class="mb-0">Pilih Metode Pembayaran</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="bank" value="Transfer Bank" required>
                            <label class="form-check-label" for="bank">
                                <i class="bi bi-bank"></i> Transfer Bank
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="COD" required>
                            <label class="form-check-label" for="cod">
                                <i class="bi bi-cash-coin"></i> Bayar di Tempat (COD)
                            </label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="metode_pembayaran" id="ShopeePay" value="ShopeePay" required>
                            <label class="form-check-label" for="ShopeePay">
                                <i class="bi bi-wallet2"></i> ShopeePay
                            </label>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">Bayar Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
