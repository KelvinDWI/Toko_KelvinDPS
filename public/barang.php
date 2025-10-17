<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';

// ======================= TAMBAH BARANG =======================
if (isset($_POST['tambah'])) {
    $id_barang   = strtoupper(trim($_POST['id_barang']));
    $nama_barang = trim($_POST['nama_barang']);
    $harga       = intval($_POST['harga']);
    $stok        = intval($_POST['stok']);

    if (empty($id_barang) || empty($nama_barang)) {
        $error = "⚠️ Semua field wajib diisi!";
        $action = "tambah";
    } elseif ($harga <= 0) {
        $error = "⚠️ Harga harus lebih dari 0!";
        $action = "tambah";
    } elseif ($stok < 0) {
        $error = "⚠️ Stok tidak boleh negatif!";
        $action = "tambah";
    } else {
        $cek = $conn->query("SELECT id_barang FROM barang WHERE id_barang='$id_barang'");
        if ($cek && $cek->num_rows > 0) {
            $error = "❌ ID Barang '$id_barang' sudah digunakan!";
            $action = "tambah";
        } else {
            $stmt = $conn->prepare("INSERT INTO barang (id_barang, nama_barang, harga, stok) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $id_barang, $nama_barang, $harga, $stok);
            $stmt->execute();
            $stmt->close();
            $success = "✅ Barang berhasil ditambahkan!";
            $action = "tambah";
        }
    }
}

// ======================= HAPUS BARANG =======================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = $conn->query("SELECT id_barang FROM barang WHERE id_barang='$id'");
    if ($cek->num_rows == 0) {
        $error = "❌ Data barang tidak ditemukan!";
        $action = "hapus";
    } else {
        $conn->query("DELETE FROM barang WHERE id_barang='$id'");
        $success = "🗑️ Barang berhasil dihapus!";
        $action = "hapus";
    }
}

// ======================= UPDATE BARANG =======================
if (isset($_POST['update'])) {
    $id_lama     = $_POST['id_lama'];
    $id_barang   = strtoupper(trim($_POST['id_barang']));
    $nama_barang = trim($_POST['nama_barang']);
    $harga       = intval($_POST['harga']);
    $stok        = intval($_POST['stok']);

    if (empty($id_barang) || empty($nama_barang)) {
        $error = "⚠️ Semua field wajib diisi!";
        $action = "edit";
    } elseif ($harga <= 0) {
        $error = "⚠️ Harga harus lebih dari 0!";
        $action = "edit";
    } elseif ($stok < 0) {
        $error = "⚠️ Stok tidak boleh negatif!";
        $action = "edit";
    } else {
        $cek = $conn->query("SELECT id_barang FROM barang WHERE id_barang='$id_barang' AND id_barang!='$id_lama'");
        if ($cek->num_rows > 0) {
            $error = "❌ ID Barang '$id_barang' sudah digunakan oleh data lain!";
            $action = "edit";
        } else {
            $stmt = $conn->prepare("UPDATE barang SET id_barang=?, nama_barang=?, harga=?, stok=? WHERE id_barang=?");
            $stmt->bind_param("ssiss", $id_barang, $nama_barang, $harga, $stok, $id_lama);
            $stmt->execute();
            $stmt->close();
            $success = "✅ Barang berhasil diperbarui!";
            $action = "edit";
        }
    }
}

// ======================= AMBIL DATA BARANG =======================
$result = $conn->query("SELECT * FROM barang ORDER BY id_barang ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="text-dark mb-0">Data Barang</h2>
  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
    <i class="bi bi-plus-lg me-1"></i> Tambah Data
  </button>
</div>

<!-- ======================= TABEL DATA BARANG ======================= -->
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="text-uppercase"><?= htmlspecialchars($row['id_barang']) ?></td>
                <td class="text-uppercase"><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($row['stok']) ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_barang'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <a href="?hapus=<?= $row['id_barang'] ?>" class="btn btn-sm btn-danger btn-hapus" data-nama="<?= htmlspecialchars($row['nama_barang']) ?>">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data barang.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ======================= MODAL TAMBAH ======================= -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title">Tambah Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>ID Barang</label>
            <input type="text" name="id_barang" class="form-control" placeholder="Contoh: B001" required>
          </div>
          <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================= MODAL EDIT ======================= -->
<?php
$result->data_seek(0);
while ($row = $result->fetch_assoc()):
?>
<div class="modal fade" id="editModal<?= $row['id_barang'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title">Edit Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_lama" value="<?= $row['id_barang'] ?>">
          <div class="mb-3">
            <label>ID Barang</label>
            <input type="text" name="id_barang" class="form-control"
                   value="<?= htmlspecialchars($row['id_barang']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control"
                   value="<?= htmlspecialchars($row['nama_barang']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control"
                   value="<?= htmlspecialchars($row['harga']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control"
                   value="<?= htmlspecialchars($row['stok']) ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update" class="btn btn-warning">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endwhile; ?>

<!-- ======================= SWEETALERT JS ======================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Konfirmasi Hapus
  const hapusButtons = document.querySelectorAll('.btn-hapus');
  hapusButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const url = this.getAttribute('href');
      const nama = this.dataset.nama;

      Swal.fire({
        title: 'Hapus Barang?',
        text: `Apakah kamu yakin ingin menghapus "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d'
      }).then(result => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
});
</script>

<?php if (isset($success) || isset($error)): ?>
<script>
Swal.fire({
  title: "<?= isset($success) ? 'Berhasil!' : 'Gagal!' ?>",
  text: "<?= isset($success) ? addslashes($success) : addslashes($error) ?>",
  icon: "<?= isset($success) ? 'success' : 'error' ?>",
  showCloseButton: true,
  confirmButtonText: "Tutup",
  confirmButtonColor: "<?= isset($success) ? '#198754' : '#d33' ?>"
}).then(() => {
  const action = "<?= $action ?? '' ?>";
  // reload hanya untuk tambah & hapus
  if (action === "tambah" || action === "hapus") {
    window.location = 'barang.php';
  }
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
