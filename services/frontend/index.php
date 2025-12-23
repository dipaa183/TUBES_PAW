<?php
session_start();
include_once __DIR__ . '/../backend/config/db.php';
include_once __DIR__ . '/../backend/functions/pemesanan.php';

$recent_orders = getAllPemesanan();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotokopi Online - Cetak Dokumen Mudah & Cepat</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header class="gradient-bg text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Fotokopi Online</h1>
            <p class="lead mb-0">Cetak dokumen Anda dengan mudah dan cepat, kapan saja dan di mana saja dengan kualitas
                terbaik</p>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Fotokopi Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"
                            href="index.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'list_pemesanan.php' ? 'active' : '' ?>"
                                href="pages/list_pemesanan.php">
                                <i class="fas fa-clipboard-list me-1"></i> Pesanan
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Menu ketika user sudah login -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>
                                        Logout</a></li>
                            </ul>
                        </li>

                        <?php if ($_SESSION['role'] !== 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="cart.php">
                                    <i class="fas fa-shopping-cart me-1"></i> Keranjang
                                    <?php if (!empty($_SESSION['cart'])): ?>
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                            <?= count($_SESSION['cart']) ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Menu ketika user belum login -->
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>"
                                href="login.php">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'register.php' ? 'active' : '' ?>"
                                href="register.php">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <section class="hero-section rounded-3 p-5 text-center mb-5 shadow">
            <h2 class="display-5 fw-bold mb-4">Layanan Fotokopi Online Profesional</h2>
            <p class="lead mb-4">Kami menyediakan solusi pencetakan dokumen dengan kualitas terbaik, harga kompetitif,
                dan proses yang cepat. Upload file Anda dan biarkan kami yang mengurus sisanya!</p>
            <a href="pages/add_pemesanan.php" class="btn btn-primary btn-lg px-4">Buat Pesanan Sekarang</a>
        </section>

        <section class="mb-5">
            <h2 class="text-center section-title">Layanan Kami</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-card card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h3 class="h4 mb-3">Warna Premium</h3>
                            <p class="mb-0">Cetak berwarna dengan kualitas tinggi menggunakan printer laser terbaik
                                untuk hasil yang tajam dan warna yang hidup.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 class="h4 mb-3">Hitam Putih Ekonomis</h3>
                            <p class="mb-0">Solusi hemat untuk dokumen sehari-hari dengan hasil cetak yang jelas dan
                                harga terjangkau.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card card h-100 shadow">
                        <div class="card-body text-center p-4">
                            <div class="service-icon mb-3">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="h4 mb-3">Berkas Rahasia</h3>
                            <p class="mb-0">Dokumen sensitif Anda dijamin kerahasiaannya dengan sistem penyimpanan dan
                                penghancuran yang aman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="text-center section-title">Keunggulan Kami</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex align-items-start">
                            <div class="feature-icon me-3">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Proses Cepat</h3>
                                <p class="mb-0 text-muted">Dokumen Anda dicetak dan dikirim dalam waktu singkat dengan
                                    sistem otomatis kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex align-items-start">
                            <div class="feature-icon me-3">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Pengiriman Cepat</h3>
                                <p class="mb-0 text-muted">Gratis ongkir untuk wilayah tertentu atau bisa diambil
                                    langsung di tempat kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex align-items-start">
                            <div class="feature-icon me-3">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h3 class="h5 mb-2">Keamanan Data</h3>
                                <p class="mb-0 text-muted">File Anda dijamin aman dan akan dihapus otomatis setelah
                                    proses pencetakan selesai.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center py-4">
                        <div class="stat-number">5.000+</div>
                        <div class="text-muted">Dokumen Dicetak</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center py-4">
                        <div class="stat-number">98%</div>
                        <div class="text-muted">Kepuasan Pelanggan</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center py-4">
                        <div class="stat-number">24/7</div>
                        <div class="text-muted">Layanan Online</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center py-4">
                        <div class="stat-number">30+</div>
                        <div class="text-muted">Jenis Kertas</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5">
            <h2 class="text-center section-title">Apa Kata Pelanggan</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card card h-100 shadow-sm bg-light">
                        <div class="card-body">
                            <p class="mb-4">"Pelayanan sangat cepat dan hasil cetakan rapi. Saya selalu menggunakan
                                layanan ini untuk kebutuhan dokumen penting kantor."</p>
                            <div class="d-flex align-items-center">
                                <img src="https://randomuser.me/api/portraits/women/43.jpg" class="rounded-circle me-3"
                                    width="50" height="50" alt="Customer">
                                <div>
                                    <h5 class="mb-0">Sarah Wijaya</h5>
                                    <small class="text-muted">Marketing Manager</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card card h-100 shadow-sm bg-light">
                        <div class="card-body">
                            <p class="mb-4">"Harga terjangkau dengan kualitas premium. Sangat membantu untuk mencetak
                                tugas-tugas kuliah dalam jumlah banyak."</p>
                            <div class="d-flex align-items-center">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle me-3"
                                    width="50" height="50" alt="Customer">
                                <div>
                                    <h5 class="mb-0">Budi Santoso</h5>
                                    <small class="text-muted">Mahasiswa</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card card h-100 shadow-sm bg-light">
                        <div class="card-body">
                            <p class="mb-4">"Sistem online yang mudah digunakan dan pengiriman tepat waktu. Dokumen
                                rahasia perusahaan saya pun aman."</p>
                            <div class="d-flex align-items-center">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg" class="rounded-circle me-3"
                                    width="50" height="50" alt="Customer">
                                <div>
                                    <h5 class="mb-0">Dewi Kurnia</h5>
                                    <small class="text-muted">Direktur Perusahaan</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="gradient-bg text-white rounded-3 p-5 text-center mb-5 shadow">
            <h2 class="display-5 fw-bold mb-4">Siap Mencetak Dokumen Anda?</h2>
            <p class="lead mb-4">Bergabunglah dengan ribuan pelanggan yang telah mempercayakan kebutuhan pencetakan
                mereka kepada kami.</p>
            <a href="pages/add_pemesanan.php" class="btn btn-light btn-lg px-4">Pesan Sekarang</a>
        </section>
    </main>

    <footer class="footer text-white py-5 mt-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h3 class="h4 mb-4">Tentang Kami</h3>
                    <p class="mb-4">Fotokopi Online menyediakan layanan pencetakan dokumen berkualitas dengan sistem
                        online yang mudah dan cepat.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-icon bg-secondary text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon bg-secondary text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon bg-secondary text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon bg-secondary text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h3 class="h4 mb-4">Link Cepat</h3>
                    <ul class="list-unstyled footer-links">
                        <li class="mb-2"><a href="index.php" class="text-white-50">Beranda</a></li>
                        <li class="mb-2"><a href="pages/list_pemesanan.php" class="text-white-50">Pesanan</a></li>
                        <li class="mb-2"><a href="login.php" class="text-white-50">Login</a></li>
                        <li class="mb-2"><a href="register.php" class="text-white-50">Register</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h3 class="h4 mb-4">Kontak Kami</h3>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Jl. Teknologi No. 123, Jakarta</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> (021) 1234-5678</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@fotokopionline.com</li>
                        <li class="mb-0"><i class="fas fa-clock me-2"></i> Buka 24/7 Online</li>
                    </ul>
                </div>
                <div class="col-lg-3">

                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="text-center py-3">
                <p class="mb-0 text-white-50">&copy; <?= date('Y') ?> Fotokopi Online. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'chat_component.php'; ?>
</body>

</html>