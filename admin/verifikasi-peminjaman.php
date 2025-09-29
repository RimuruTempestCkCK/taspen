<?php
require_once '../init.php';
require_once '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// PROSES SETUJUI / TOLAK DITEMPATKAN SEBELUM OUTPUT APAPUN
if (isset($_GET['setujui'])) {
    $id = $_GET['setujui'];
    mysqli_query($conn, "UPDATE peminjaman SET status='Dipinjam' WHERE id=$id");
    $_SESSION['flash'] = "Peminjaman telah disetujui.";
    header("Location: verifikasi-peminjaman.php");
    exit;
}

if (isset($_GET['tolak'])) {
    $id = $_GET['tolak'];
    mysqli_query($conn, "UPDATE peminjaman SET status='Ditolak' WHERE id=$id");
    $_SESSION['flash'] = "Peminjaman telah ditolak.";
    header("Location: verifikasi-peminjaman.php");
    exit;
}


// Ambil data peminjaman yang masih menunggu verifikasi
$query = mysqli_query($conn, "
  SELECT p.id, p.tanggal_pinjam, p.status,
         u.nama AS nama_user, d.nama_dosir
  FROM peminjaman p
  JOIN users u ON p.id_user = u.id
  JOIN dosir d ON p.id_dosir = d.id
  WHERE p.status = 'Menunggu Persetujuan'
  ORDER BY p.tanggal_pinjam DESC
");



// Baru setelah itu include layout
require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
require_once BASE_PATH . '/layouts/topbar.php';

?>

<div class="main-content py-4">
  <div class="container">
    <br><br><br>
    <?php if (isset($_SESSION['flash'])): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?= $_SESSION['flash'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

    <h5 class="fw-semibold mb-3">Verifikasi Peminjaman</h5>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table align-middle text-nowrap mb-0">
            <thead>
              <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Dosir</th>
                <th>Tanggal Pinjam</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                <td><?= htmlspecialchars($row['nama_dosir']) ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                <td><span class="badge bg-warning"><?= $row['status'] ?></span></td>
                <td>
                  <a href="?setujui=<?= $row['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui peminjaman ini?')">Setujui</a>
                  <a href="?tolak=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak peminjaman ini?')">Tolak</a>
                </td>
              </tr>
              <?php endwhile; ?>
              <?php if (mysqli_num_rows($query) === 0): ?>
              <tr><td colspan="6" class="text-center">Tidak ada permintaan baru.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

</div>
<?php include BASE_PATH . '/layouts/footer.php'; ?>

