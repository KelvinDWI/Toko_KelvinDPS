<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Toko Kelvin - Website Penjualan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- ✅ Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- ✅ Custom CSS -->
  <link rel="stylesheet" href="/toko_kelvindps/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body class="">

<?php
  $current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-4 sticky-top bg-light">
  <div class="container">
    <a class="navbar-brand fw-medium" href="/toko_kelvindps/index.php">Toko Kelvin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index.php') ? 'text-primary fw-semibold' : ''; ?>" href="/toko_kelvindps/index.php">Dashboard</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'barang.php') ? 'text-primary fw-semibold' : ''; ?>" href="/toko_kelvindps/public/barang.php">Barang</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'pembeli.php') ? 'text-primary fw-semibold' : ''; ?>" href="/toko_kelvindps/public/pembeli.php">Pembeli</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'transaksi.php') ? 'text-primary fw-semibold' : ''; ?>" href="/toko_kelvindps/public/transaksi.php">Transaksi</a>
        </li>

        <!-- Tombol Logout -->
        <li class="nav-item ms-3">
          <a href="/toko_kelvindps/public/logout.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="container">
