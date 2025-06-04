<?php
session_start();
require_once '../functions/pemesanan.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$status_mapping_display = [
    'pending' => 'Menunggu',
    'processing' => 'Diproses',
    'completed' => 'Selesai',
];

$status_mapping_value = [
    'Menunggu' => 'pending',
    'Diproses' => 'processing',
    'Selesai' => 'completed',
];

if (isset($_GET['hapus'])) {
    deletePemesanan($_GET['hapus']);
    header("Location: list_pemesanan.php");
    exit;
}

if (isset($_GET['update_status']) && isset($_GET['id']) && isset($_GET['status'])) {
    $status = $_GET['status'];
    if (array_key_exists($status, $status_mapping_value)) {
        updatePemesananStatus($_GET['id'], $status_mapping_value[$status]);
    }
    header("Location: list_pemesanan.php");
    exit;
}

$pemesanans = getAllPemesanan();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .dropdown-menu {
            min-width: 10rem;
        }

        .status-badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
</head>

<body class="container pt-4">
    <h2 class="mb-4">Daftar Pemesanan</h2>
    <div class="mb-4">
        <a href="../index.php" class="btn btn-secondary me-2"><i class="fas fa-home me-1"></i> Beranda</a>
        <a href="add_pemesanan.php" class="btn btn-primary me-2"><i class="fas fa-plus me-1"></i> Tambah Pemesanan</a>
        <a href="../export/export_pdf.php" class="btn btn-danger me-2"><i class="fas fa-file-pdf me-1"></i> PDF</a>
        <a href="../export/export_excel.php" class="btn btn-success"><i class="fas fa-file-excel me-1"></i> Excel</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th width="5%">#</th>
                    <th>Nama Pelanggan</th>
                    <th>File Upload</th>
                    <th>Jenis Pemesanan</th>
                    <th>Jenis Warna</th>
                    <th>Ukuran Kertas</th>
                    <th width="8%">Jumlah</th>
                    <th width="10%">Total Harga</th>
                    <th width="10%">Status</th>
                    <th width="10%">Tanggal</th>
                    <th width="18%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($pemesanans) == 0): ?>
                    <tr>
                        <td colspan="11" class="text-center py-4">Tidak ada data pemesanan</td>
                    </tr>
                <?php else:
                    foreach ($pemesanans as $i => $p):
                        $status_value = $p['status'];
                        $status_display = $status_mapping_display[$status_value] ?? ucfirst($status_value);
                        $badge_class = match ($status_value) {
                            'pending' => 'warning',
                            'processing' => 'info',
                            'completed' => 'success',
                            default => 'secondary',
                        };
                        ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($p['nama_pelanggan']) ?></td>
                            <td>
                                <?php if ($p['file_upload']): ?>
                                    <a href="../uploads/<?= htmlspecialchars($p['file_upload']) ?>" target="_blank"
                                        class="text-primary text-decoration-none">
                                        <i class="fas fa-file me-1"></i><?= htmlspecialchars($p['file_upload']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($p['jenis_pemesanan']) ?></td>
                            <td><?= htmlspecialchars($p['jenis_warna']) ?></td>
                            <td><?= htmlspecialchars($p['ukuran_kertas']) ?></td>
                            <td class="text-center"><?= (int) $p['jumlah_copy'] ?></td>
                            <td class="text-end">Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge status-badge bg-<?= $badge_class ?>">
                                    <?= htmlspecialchars($status_display) ?>
                                </span>
                            </td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_pemesanan'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <div class="dropdown d-inline-block me-1">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton<?= $p['id'] ?>" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $p['id'] ?>">
                                            <?php foreach ($status_mapping_value as $label => $value): ?>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="list_pemesanan.php?update_status=1&id=<?= $p['id'] ?>&status=<?= $label ?>">
                                                        <i class="fas fa-circle me-1 text-<?=
                                                            $value == 'pending' ? 'warning' :
                                                            ($value == 'processing' ? 'info' : 'success')
                                                            ?>"></i>
                                                        <?= $label ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <a href="edit_pemesanan.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="list_pemesanan.php?hapus=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger"
                                        title="Hapus" onclick="return confirm('Yakin hapus pemesanan ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
