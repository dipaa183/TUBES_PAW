<?php
session_start();
include_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/functions/pemesanan.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['cart'])) {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $tanggal = date('Y-m-d');
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        foreach ($_SESSION['cart'] as $item) {

            $data = [
                'user_id' => $user_id,
                'nama_pelanggan' => $nama_pelanggan,
                'file_upload' => $item['file_upload'],
                'jenis_pemesanan' => $item['jenis_pemesanan'],
                'jenis_warna' => $item['jenis_warna'],
                'ukuran_kertas' => $item['ukuran_kertas'],
                'jumlah_copy' => $item['jumlah_copy'],
                'catatan' => $item['catatan'],
                'tanggal_pemesanan' => $tanggal,
                'total_harga' => $item['total_harga'], 
                'status' => 'pending'
            ];

            if (!addPemesanan($data)) {
                throw new Exception("Gagal menyimpan pesanan");
            }
        }

        // Commit transaksi jika semua berhasil
        mysqli_commit($conn);

        // Bersihkan keranjang
        $_SESSION['cart'] = [];

        // Redirect ke halaman terima kasih
        header('Location: thanks.php');
        exit;
    } catch (Exception $e) {
        // Rollback jika ada error
        mysqli_rollback($conn);
        echo "Terjadi kesalahan saat memproses pesanan: " . $e->getMessage();
        exit;
    }
} else {
    // Jika cart kosong atau bukan POST
    header('Location: cart.php');
    exit;
}