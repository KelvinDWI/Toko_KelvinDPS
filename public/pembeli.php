<?php
require_once __DIR__ . '/../config/database.php';
include __DIR__ . '/../includes/header.php';

// ======================= PENCARIAN DATA =======================
$search = $_GET['search'] ?? '';
$where = '';
if (!empty($search)) {
    $search_safe = $conn->real_escape_string($search);
    $where = "WHERE nama_pembeli LIKE '%$search_safe%'";
}

// ======================= TAMBAH PEMBELI =======================
if (isset($_POST['tambah'])) {
    $id_pembeli   = strtoupper(trim($_POST['id_pembeli']));
    $nama_pembeli = trim($_POST['nama_pembeli']);
    $alamat       = trim($_POST['alamat']);
    $no_hp        = trim($_POST['no_hp']);

    if (empty($id_pembeli) || empty($nama_pembeli)) {
        $error = "ID Pembeli dan Nama Pembeli wajib diisi!";
    } elseif (!empty($no_hp) && !preg_match('/^[0-9]+$/', $no_hp)) {
        $error = "Nomor HP hanya boleh berisi angka!";
    } else {
        $cekID = $conn->query("SELECT id_pembeli FROM pembeli WHERE id_pembeli = '$id_pembeli'");
        if ($cekID && $cekID->num_rows > 0) {
            $error = "ID Pembeli '$id_pembeli' sudah digunakan. Gunakan ID lain!";
        } else {
            $stmt = $conn->prepare("INSERT INTO pembeli (id_pembeli, nama_pembeli, alamat, no_hp) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $id_pembeli, $nama_pembeli, $alamat, $no_hp);
            $stmt->execute();
            $stmt->close();
            $success = "Data pembeli berhasil ditambahkan!";
        }
    }
}

// ======================= HAPUS PEMBELI =======================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $cek = $conn->query("SELECT id_pembeli FROM pembeli WHERE id_pembeli='$id'");
    if ($cek->num_rows == 0) {
        $error = "Data pembeli dengan ID '$id' tidak ditemukan!";
    } else {
        $conn->query("DELETE FROM pembeli WHERE id_pembeli='$id'");
        $success = "Data pembeli berhasil dihapus!";
    }
}

// ======================= UPDATE PEMBELI =======================
if (isset($_POST['update'])) {
    $id_lama      = $_POST['id_lama'];
    $id_pembeli   = strtoupper(trim($_POST['id_pembeli']));
    $nama_pembeli = trim($_POST['nama_pembeli']);
    $alamat       = trim($_POST['alamat']);
    $no_hp        = trim($_POST['no_hp']);

    if (empty($id_pembeli) || empty($nama_pembeli)) {
        $error = "ID Pembeli dan Nama Pembeli wajib diisi!";
    } elseif (!empty($no_hp) && !preg_match('/^[0-9]+$/', $no_hp)) {
        $error = "Nomor HP hanya boleh berisi angka!";
    } else {
        $cek = $conn->query("SELECT id_pembeli FROM pembeli WHERE id_pembeli='$id_pembeli' AND id_pembeli != '$id_lama'");
        if ($cek->num_rows > 0) {
            $error = "ID Pembeli '$id_pembeli' sudah digunakan oleh data lain!";
        } else {
            $stmt = $conn->prepare("UPDATE pembeli SET id_pembeli=?, nama_pembeli=?, alamat=?, no_hp=? WHERE id_pembeli=?");
            $stmt->bind_param("sssss", $id_pembeli, $nama_pembeli, $alamat, $no_hp, $id_lama);
            $stmt->execute();
            $stmt->close();
            $success = "Data pembeli berhasil diperbarui!";
        }
    }
}

// ======================= AMBIL DATA PEMBELI =======================
$result = $conn->query("SELECT * FROM pembeli $where ORDER BY id_pembeli ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
  <h2 class="text-dark mb-2 mb-md-0">Data Pembeli</h2>
  <div class="d-flex align-items-center gap-2 flex-wrap">

    <!-- 🔍 Form Pencarian -->
    <form class="d-flex" method="get" action="pembeli.php">
        <input 
            type="text" 
            name="search" 
            class="form-control form-control-sm" 
            placeholder="Cari Nama Pembeli" 
            value="<?= htmlspecialchars($search) ?>" 
            style="width: 220px;"
        >
        <button class="btn btn-sm btn-secondary ms-1" type="submit">Cari</button>
        <a href="pembeli.php" class="btn btn-sm btn-outline-secondary ms-1">Reset</a>
    </form>

    <!-- ➕ Tombol Tambah Data -->
    <button class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
      <i class="bi bi-plus-lg me-1"></i> Tambah Data
    </button>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>ID Pembeli</th>
            <th>Nama Pembeli</th>
            <th>Alamat</th>
            <th>No. HP</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td class="text-uppercase"><?= htmlspecialchars($row['id_pembeli']) ?></td>
                <td><?= htmlspecialchars($row['nama_pembeli']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td><?= htmlspecialchars($row['no_hp']) ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_pembeli'] ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <a href="?hapus=<?= $row['id_pembeli'] ?>" 
                     class="btn btn-sm btn-danger btn-hapus"
                     data-nama="<?= htmlspecialchars($row['nama_pembeli']) ?>">
                     <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-3">Belum ada data pembeli.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ======================= Modal Tambah Pembeli ======================= -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title">Tambah Data Pembeli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>ID Pembeli</label>
            <input type="text" name="id_pembeli" class="form-control text-uppercase" placeholder="Contoh: P001" required>
          </div>
          <div class="mb-3">
            <label>Nama Pembeli</label>
            <input type="text" name="nama_pembeli" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required></textarea>
          </div>
          <div class="mb-3">
            <label>No. HP</label>
            <input type="text" name="no_hp" class="form-control" placeholder="Hanya angka" pattern="[0-9]*">
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

<!-- ======================= Modal Edit Pembeli ======================= -->
<?php
$result->data_seek(0);
while ($row = $result->fetch_assoc()):
?>
<div class="modal fade" id="editModal<?= $row['id_pembeli'] ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST">
        <div class="modal-header bg-light text-dark">
          <h5 class="modal-title">Edit Data Pembeli</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_lama" value="<?= $row['id_pembeli'] ?>">
          <div class="mb-3">
            <label>ID Pembeli</label>
            <input type="text" name="id_pembeli" class="form-control text-uppercase" value="<?= htmlspecialchars($row['id_pembeli']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Nama Pembeli</label>
            <input type="text" name="nama_pembeli" class="form-control" value="<?= htmlspecialchars($row['nama_pembeli']) ?>" required>
          </div>
          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($row['alamat']) ?></textarea>
          </div>
          <div class="mb-3">
            <label>No. HP</label>
            <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($row['no_hp']) ?>" pattern="[0-9]*">
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


<!-- ================== SWEETALERT ================== -->
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
        title: 'Hapus Pembeli?',
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
  allowOutsideClick: false,
  allowEscapeKey: false,
  confirmButtonText: "Tutup",
  confirmButtonColor: "<?= isset($success) ? '#198754' : '#d33' ?>"
}).then(() => {
  const action = "<?= $action ?? '' ?>";
  if (action === "tambah" || action === "edit" || action === "hapus") {
    window.location = 'pembeli.php';
  }
});
</script>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
