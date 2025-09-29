<?php
require_once '../init.php';
require_once '../koneksi.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'pimpinan')) {
    header("Location: ../login.php");
    exit;
}

// Inisialisasi nilai default
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

// Filter query jika tanggal dipilih
$whereClause = '';
if (!empty($tanggal_awal) && !empty($tanggal_akhir)) {
    $tanggal_awal_sql = mysqli_real_escape_string($conn, $tanggal_awal);
    $tanggal_akhir_sql = mysqli_real_escape_string($conn, $tanggal_akhir);
    $whereClause = "WHERE p.tanggal_pinjam BETWEEN '$tanggal_awal_sql' AND '$tanggal_akhir_sql'";
}

// Query data
$query = mysqli_query($conn, "
    SELECT p.id, u.nama AS nama_user, d.nama_dosir, 
           p.tanggal_pinjam, p.tanggal_kembali, p.status
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id
    JOIN dosir d ON p.id_dosir = d.id
    $whereClause
    ORDER BY p.tanggal_pinjam DESC
");

require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
require_once BASE_PATH . '/layouts/topbar.php';
?>

<div class="main-content py-4">
  <div class="container">
    <br> <br>
    <h4 class="mb-4">Laporan Peminjaman Dosir</h4>

    <!-- Form Filter -->
    <form class="row g-3 mb-4" method="GET">
  <div class="col-md-4">
    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?= htmlspecialchars($tanggal_awal) ?>">
  </div>
  <div class="col-md-4">
    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir) ?>">
  </div>
  <div class="col-md-4 d-flex align-items-end">
    <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
    <a href="laporan-peminjaman.php" class="btn btn-secondary me-2">Reset</a>
    <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)) : ?>
      <a href="cetak_laporan_peminjaman.php?tanggal_awal=<?= $tanggal_awal ?>&tanggal_akhir=<?= $tanggal_akhir ?>" target="_blank" class="btn btn-success">Cetak PDF</a>
    <?php endif; ?>
  </div>
</form>


    <!-- Tabel Laporan -->
    <div class="card">
      <div class="card-body">
        <?php if (mysqli_num_rows($query) > 0): ?>
          <div class="table-responsive">
            <table class="table table-bordered text-nowrap">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Nama Peminjam</th>
                  <th>Dosir</th>
                  <th>Tanggal Pinjam</th>
                  <th>Tanggal Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                  <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_user']) ?></td>
                    <td><?= htmlspecialchars($row['nama_dosir']) ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td>
                      <?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '<span class="text-muted">-</span>' ?>
                    </td>
                    <td><span class="badge bg-info"><?= $row['status'] ?></span></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">Tidak ada data peminjaman untuk rentang tanggal tersebut.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include BASE_PATH . '/layouts/footer.php'; ?>
