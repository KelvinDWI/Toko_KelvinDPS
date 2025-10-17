  <footer class="text-center mt-5 py-3 border-top small text-secondary ">
    &copy; <?= date('Y') ?> <strong>Toko Kelvin</strong> — Aplikasi Penjualan Sederhana
  </footer>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const hapusButtons = document.querySelectorAll('.btn-hapus');

  hapusButtons.forEach(button => {
    button.addEventListener('click', function(event) {
      event.preventDefault();
      const url = this.getAttribute('href');
      const nama = this.dataset.nama;

      Swal.fire({
        title: 'Hapus Data?',
        text: `Apakah kamu yakin ingin menghapus data "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
});
</script>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
