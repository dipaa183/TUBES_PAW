<?php
session_start();
require_once '../functions/pemesanan.php';

$error = "";

// Redirect jika belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_path = '../uploads/';
    $filename = '';

    // Cek folder upload
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    // Proses upload file
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
        $originalName = basename($_FILES['file_upload']['name']);
        $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
        $target_file = $upload_path . $filename;

        if (!move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
            $error = "Gagal mengunggah file!";
        }
    } else {
        $error = "File tidak valid atau belum diunggah.";
    }

    // Simpan ke session cart
    if ($error === "") {
        $jenis_pemesanan = isset($_POST['jenis_pemesanan']) ? implode(',', $_POST['jenis_pemesanan']) : '';

        $total_harga = calculatePrice(
            $_POST['jenis_warna'],
            $_POST['ukuran_kertas'],
            $_POST['jumlah_copy']
        );

        $data = [
            'user_id' => $_SESSION['user_id'],
            'nama_pelanggan' => htmlspecialchars($_POST['nama_pelanggan']),
            'file_upload' => $filename,
            'jenis_pemesanan' => htmlspecialchars($jenis_pemesanan),
            'jenis_warna' => $_POST['jenis_warna'],
            'ukuran_kertas' => $_POST['ukuran_kertas'],
            'jumlah_copy' => (int) $_POST['jumlah_copy'],
            'catatan' => htmlspecialchars($_POST['catatan']),
            'tanggal_pemesanan' => $_POST['tanggal_pemesanan'],
            'total_harga' => $total_harga,
            'status' => 'pending'
        ];

        $_SESSION['cart'][] = $data;
        header("Location: ../cart.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Pemesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body class="container pt-4">

    <h2>Tambah Pemesanan</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Upload File</label>
            <input type="file" name="file_upload" class="form-control-file" required>
        </div>
        <div class="form-group">
            <label>Jenis Pemesanan</label><br>
            <input type="checkbox" name="jenis_pemesanan[]" value="Print"> Print
            <input type="checkbox" name="jenis_pemesanan[]" value="Laminasi"> Laminasi
            <input type="checkbox" name="jenis_pemesanan[]" value="Jilid"> Jilid
        </div>
        <div class="form-group">
            <label>Jenis Warna</label><br>
            <input type="radio" name="jenis_warna" value="Hitam Putih" required> Hitam Putih
            <input type="radio" name="jenis_warna" value="Berwarna" required> Berwarna
        </div>
        <div class="form-group">
            <label>Ukuran Kertas</label>
            <select name="ukuran_kertas" class="form-control" required>
                <option value="">-- Pilih Ukuran --</option>
                <option value="A4">A4</option>
                <option value="F4">F4</option>
                <option value="A3">A3</option>
                <option value="Legal">Legal</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Copy</label>
            <input type="number" name="jumlah_copy" class="form-control" required min="1">
        </div>
        <div class="form-group">
            <label>Catatan Tambahan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label>Tanggal Pemesanan</label>
            <input type="date" name="tanggal_pemesanan" class="form-control" required value="<?= date('Y-m-d') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="list_pemesanan.php" class="btn btn-secondary">Kembali</a>
    </form>

</body>

</html>