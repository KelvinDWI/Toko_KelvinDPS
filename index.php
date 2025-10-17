<?php

session_start();

if (!isset($_SESSION['login'])) {
  header("Location: /toko_kelvindps/public/login.php");
  exit;
}

// public/index.php
require_once __DIR__ . '../config/database.php';
include __DIR__ . '../includes/header.php';

// Total transaksi
$res = $conn->query("SELECT COUNT(*) AS total FROM transaksi");
$total_transaksi = $res->fetch_assoc()['total'] ?? 0;

// Total pendapatan
$res = $conn->query("SELECT COALESCE(SUM(total_harga),0) AS pendapatan FROM transaksi");
$total_pendapatan = $res->fetch_assoc()['pendapatan'] ?? 0;

// Barang terlaris
$res = $conn->query("
  SELECT b.nama_barang, SUM(t.jumlah) AS total_jual
  FROM transaksi t
  JOIN barang b ON t.id_barang = b.id_barang
  GROUP BY b.id_barang
  ORDER BY total_jual DESC
  LIMIT 1
");
$barang_terlaris = $res->fetch_assoc();
?>

<div class="container py-4 fade-in">
  <!-- Header -->
  <div class="dashboard-header d-flex justify-content-between align-items-center">
    <div>
      <h2 class="fw-bold text-dark mb-1">Dashboard Penjualan</h2>
      <p class="text-muted mb-0">Lihat performa toko dan aktivitas transaksi terkini.</p>
    </div>
    <span class="badge bg-primary-subtle text-primary p-2 px-3">
      <i class="bi bi-calendar-event me-2"></i> <?= date('d M Y') ?>
    </span>
  </div>

  <!-- 🔹 Layout Kiri-Kanan -->
  <div class="dashboard-wrapper">

    <!-- 🔸 Kiri: Statistik -->
    <div class="left-panel">

      <!-- Total Transaksi -->
      <div class="stat-card shadow-sm border-0 p-4 bg-white">
        <div class="d-flex align-items-center">
          <div class="icon-square bg-gradient-primary text-white me-3">
            <i class="bi bi-receipt fs-4"></i>
          </div>
          <div>
            <p class="text-muted small mb-1">Total Transaksi</p>
            <h3 class="fw-bold mb-0"><?= $total_transaksi ?></h3>
          </div>
        </div>
      </div>

      <!-- Total Pendapatan -->
      <div class="stat-card shadow-sm border-0 p-4 bg-white">
        <div class="d-flex align-items-center">
          <div class="icon-square bg-gradient-success text-white me-3">
            <i class="bi bi-cash-coin fs-4"></i>
          </div>
          <div>
            <p class="text-muted small mb-1">Total Pendapatan</p>
            <h4 class="fw-bold text-success mb-0">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h4>
          </div>
        </div>
      </div>

      <!-- Barang Terlaris -->
      <div class="stat-card shadow-sm border-0 p-4 bg-white">
        <div class="d-flex align-items-center">
          <div class="icon-square bg-gradient-warning text-white me-3">
            <i class="bi bi-star-fill fs-4"></i>
          </div>
          <div>
            <p class="text-muted small mb-1">Barang Terlaris</p>
            <h6 class="fw-bold mb-0"><?= $barang_terlaris['nama_barang'] ?? '-' ?></h6>
            <small class="text-muted">Terjual <?= $barang_terlaris['total_jual'] ?? 0 ?> unit</small>
          </div>
        </div>
      </div>
    </div>

    <!-- 🔸 Kanan: Tabel Transaksi -->
    <div class="right-panel">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 px-4 py-3 d-flex justify-content-between align-items-center">
          <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-clock-history text-primary me-2"></i> Transaksi Terbaru
          </h5>
          <a href="public/transaksi.php" class="btn btn-sm btn-outline-primary rounded-pill">
            <i class="bi bi-eye me-1"></i> Lihat Semua
          </a>
        </div>
        <div class="card-body px-4 pb-4">
          <?php
          $query = "
            SELECT 
              t.id_transaksi,
              p.nama_pembeli,
              b.nama_barang,
              t.jumlah,
              t.total_harga,
              DATE_FORMAT(t.tanggal, '%d-%m-%Y') AS tanggal
            FROM transaksi t
            JOIN pembeli p ON t.id_pembeli = p.id_pembeli
            JOIN barang b ON t.id_barang = b.id_barang
            ORDER BY t.id_transaksi DESC
            LIMIT 10
          ";
          $result = $conn->query($query);
          ?>
          <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
              <thead class="table-light">
                <tr class="text-muted small text-uppercase">
                  <th>ID</th>
                  <th>Pembeli</th>
                  <th>Barang</th>
                  <th>Jumlah</th>
                  <th>Total</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                      <td class="fw-semibold text-dark"><?= htmlspecialchars($row['id_transaksi']) ?></td>
                      <td><?= htmlspecialchars($row['nama_pembeli']) ?></td>
                      <td class="text-uppercase"><?= htmlspecialchars($row['nama_barang']) ?></td>
                      <td><?= htmlspecialchars($row['jumlah']) ?></td>
                      <td class="text-success fw-semibold">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                      <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi tercatat.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include __DIR__ . '../includes/footer.php'; ?>
