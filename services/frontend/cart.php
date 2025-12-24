<?php
session_start();
if (isset($_GET['checkout_success'])) {
    echo '<div class="alert alert-success">Pesanan berhasil diproses!</div>';
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

require_once 'services/backend/functions/pemesanan.php';

// Mapping status enum -> label Indonesia
$status_mapping_display = [
    'pending' => 'Menunggu',
    'processing' => 'Diproses',
    'completed' => 'Selesai',
];

// Hapus item dari cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}

$userOrders = getUserOrders($_SESSION['user_id']);

// Total harga dari cart session
$cartTotal = 0;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
foreach ($_SESSION['cart'] as $item) {
    if (isset($item['total_harga'])) {
        $cartTotal += $item['total_harga'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Keranjang Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4">Keranjang Pemesanan</h2>

        <!-- Tampilkan Cart Session -->
        <?php if (!empty($_SESSION['cart'])): ?>
            <h4>Pesanan Baru</h4>
            <table class="table table-bordered mb-5">
                <thead>
                    <tr>
                        <th>Jenis</th>
                        <th>Warna</th>
                        <th>Ukuran</th>
                        <th>Copy</th>
                        <th>Catatan</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $i => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['jenis_pemesanan'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['jenis_warna'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['ukuran_kertas'] ?? '') ?></td>
                            <td><?= $item['jumlah_copy'] ?? '' ?></td>
                            <td><?= htmlspecialchars($item['catatan'] ?? '') ?></td>
                            <td>Rp <?= isset($item['total_harga']) ? number_format($item['total_harga'], 0, ',', '.') : '0' ?>
                            </td>
                            <td>
                                <a href="cart.php?remove=<?= $i ?>" class="btn btn-sm btn-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Total</th>
                        <th>Rp <?= number_format($cartTotal, 0, ',', '.') ?></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <form action="checkout.php" method="POST" class="mb-5">
                <input type="hidden" name="nama_pelanggan"
                    value="<?= htmlspecialchars($_SESSION['nama_pelanggan'] ?? $_SESSION['cart'][0]['nama_pelanggan'] ?? '') ?>">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success btn-lg">Checkout Sekarang</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Tampilkan Riwayat Pemesanan dari Database -->
        <?php if (!empty($userOrders)): ?>
            <h4>Riwayat Pemesanan</h4>
            <a href="index.php" class="btn btn-secondary mb-3">Beranda</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Warna</th>
                        <th>Ukuran</th>
                        <th>Copy</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userOrders as $order):
                        $status_value = $order['status'];
                        $status_display = $status_mapping_display[$status_value] ?? ucfirst($status_value);
                        $badge_class = match ($status_value) {
                            'completed' => 'success',
                            'processing' => 'warning',
                            'pending' => 'secondary',
                            default => 'dark',
                        };
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($order['tanggal_pemesanan']) ?></td>
                            <td><?= htmlspecialchars($order['jenis_pemesanan']) ?></td>
                            <td><?= htmlspecialchars($order['jenis_warna']) ?></td>
                            <td><?= htmlspecialchars($order['ukuran_kertas']) ?></td>
                            <td><?= $order['jumlah_copy'] ?></td>
                            <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-<?= $badge_class ?>">
                                    <?= htmlspecialchars($status_display) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Tombol Payment baru di bawah tabel status -->
            <div class="btn btn-primary btn-sm">
                <a href="payment.php" class="btn btn-primary btn-sm">Pembayaran</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Anda belum memiliki riwayat pemesanan.</div>
        <?php endif; ?>

        <?php if (empty($_SESSION['cart']) && empty($userOrders)): ?>
            <div class="alert alert-warning">Keranjang dan riwayat pemesanan kosong.
                <a href="pages/add_pemesanan.php" class="alert-link">Tambah pemesanan</a>.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>