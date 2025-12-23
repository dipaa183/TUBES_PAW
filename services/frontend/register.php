<?php
session_start();
include_once __DIR__ . '/../backend/config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama';
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Email sudah terdaftar';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                $success = 'Pendaftaran berhasil. Silakan <a href="login.php" class="alert-link">login</a>.';
            } else {
                $error = 'Terjadi kesalahan saat mendaftar';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Fotokopi Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Daftar Akun</h3>

                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success text-center"><?= $success ?></div>
                        <?php else: ?>
                            <form method="POST" novalidate>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Daftar</button>
                            </form>

                            <div class="text-center mt-3">
                                Sudah punya akun? <a href="login.php">Login disini</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>