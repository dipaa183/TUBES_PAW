<?php
include_once __DIR__ . '/../config/db.php';

function getAllPemesanan()
{
    global $conn;
    $sql = "SELECT * FROM pemesanan ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
function updatePemesananStatus($id, $status)
{
    global $conn;
    $sql = "UPDATE pemesanan SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    return mysqli_stmt_execute($stmt);
}

function getPemesananById($id)
{
    global $conn;
    $sql = "SELECT * FROM pemesanan WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function getUserOrders($user_id)
{
    global $conn;
    $sql = "SELECT * FROM pemesanan WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function addPemesanan($data)
{
    global $conn;
    $sql = "INSERT INTO pemesanan 
            (user_id, nama_pelanggan, file_upload, jenis_pemesanan, jenis_warna, 
             ukuran_kertas, jumlah_copy, catatan, tanggal_pemesanan, total_harga, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "isssssissds",
        $data['user_id'],
        $data['nama_pelanggan'],
        $data['file_upload'],
        $data['jenis_pemesanan'],
        $data['jenis_warna'],
        $data['ukuran_kertas'],
        $data['jumlah_copy'],
        $data['catatan'],
        $data['tanggal_pemesanan'],
        $data['total_harga'],
        $data['status']
    );
    return mysqli_stmt_execute($stmt);
}

function updatePemesanan($id, $data)
{
    global $conn;
    $sql = "UPDATE pemesanan SET 
            nama_pelanggan = ?, 
            file_upload = ?, 
            jenis_pemesanan = ?, 
            jenis_warna = ?, 
            ukuran_kertas = ?, 
            jumlah_copy = ?, 
            catatan = ?, 
            tanggal_pemesanan = ?,
            total_harga = ?,
            status = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssdsi",
        $data['nama_pelanggan'],
        $data['file_upload'],
        $data['jenis_pemesanan'],
        $data['jenis_warna'],
        $data['ukuran_kertas'],
        $data['jumlah_copy'],
        $data['catatan'],
        $data['tanggal_pemesanan'],
        $data['total_harga'],
        $data['status'],
        $id
    );
    return mysqli_stmt_execute($stmt);
}



function deletePemesanan($id)
{
    global $conn;
    $sql = "DELETE FROM pemesanan WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}


// Fungsi untuk menghitung harga
function calculatePrice($jenis_warna, $ukuran_kertas, $jumlah_copy)
{
    $harga = ($jenis_warna === 'Berwarna') ? 1000 : 500;

    // Faktor pengali ukuran kertas
    switch ($ukuran_kertas) {
        case 'A3':
            $harga *= 1.5;
            break;
        case 'Legal':
            $harga *= 1.2;
            break;
        case 'F4':
            $harga *= 1.1;
            break;
        // A4 tidak ada perubahan
    }

    return $harga * $jumlah_copy;
}
?>