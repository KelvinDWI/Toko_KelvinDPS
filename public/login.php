<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// ==================== PROSES LOGIN ====================
if (isset($_POST['login'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  if (empty($username) || empty($password)) {
    $error = "⚠️ Username dan password wajib diisi!";
  } else {
    $sql = $conn->prepare("SELECT * FROM users WHERE username=?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      if ($password === $user['password']) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        header("Location: /toko_kelvindps/index.php");
        exit;
      } else {
        $error = "❌ Password salah!";
      }
    } else {
      $error = "❌ Username tidak ditemukan!";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Toko Kelvin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #ffffff 0%, #dbeafe 50%, #60a5fa 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-wrapper {
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      display: flex;
      max-width: 850px;
      width: 100%;
    }

    .login-image {
      flex: 1;
      background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=60') center/cover no-repeat;
      display: none;
    }

    @media (min-width: 768px) {
      .login-image { display: block; }
    }

    .login-form {
      flex: 1;
      padding: 50px 40px;
    }

    .login-form h3 {
      font-weight: 700;
      color: #1e3a8a;
      margin-bottom: 15px;
      text-align: center;
    }

    .login-form p {
      text-align: center;
      color: #666;
      font-size: 14px;
      margin-bottom: 30px;
    }

    .form-control {
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 15px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      border: none;
      border-radius: 10px;
      font-weight: 600;
      padding: 10px;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
    }

    .alert {
      font-size: 14px;
      border-radius: 10px;
    }

    .brand {
      font-weight: 700;
      font-size: 22px;
      color:#2563eb; 
      text-align: center;
      margin-bottom: 25px;
    }

  </style>
</head>
<body>

  <div class="login-wrapper">
    <div class="login-image"></div>
    <div class="login-form">
      <div class="brand"><i class="bi bi-shop me-2"></i>Toko Kelvin</div>
      <h3>Selamat Datang Kembali</h3>
      <p>Masuk untuk melanjutkan ke dashboard penjualan</p>

      <?php if (isset($error)): ?>
        <div class="alert alert-danger py-2 text-center"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 mt-2">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </button>
      </form>

      <p class="text-center mt-4 text-muted" style="font-size: 13px;">© <?= date('Y') ?> Toko Kelvin. All rights reserved.</p>
    </div>
  </div>

</body>
</html>
