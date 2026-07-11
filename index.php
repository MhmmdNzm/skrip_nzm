<?php 
session_start();
require 'include/db.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = md5($_POST['password']);
    $q = $mysqli->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($q && $q->num_rows === 1) {
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $err = 'Username atau password salah';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - SECURITY RANK</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/login.css?v=<?= time(); ?>">
</head>
<body>

<div class="login-card">
    <div class="login-logo">
        <img src="assets/logo.png" alt="Logo">
        <h3>SECURITY RANK</h3>
    </div>

    <h5 class="text-center mb-3 fw-semibold text-secondary">Masuk ke Akun Anda</h5>

    <?php if($err): ?>
        <div class="alert alert-danger text-center py-2"><?= $err ?></div>
    <?php endif; ?>

    <form method="post" class="mt-3">
        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
        </div>

        <button class="btn btn-login w-100 mt-3">Masuk</button>
    </form>

    <div class="login-footer mt-4">
        <p>Sistem Penilaian Untuk Rekomendasi Satuan Pengaman Terbaik</p>
    </div>
</div>

</body>
</html>


