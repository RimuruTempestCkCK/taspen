<?php
require_once '../init.php';
require_once '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// PROSES SETUJUI
if (isset($_GET['setujui'])) {
    $id = intval($_GET['setujui']);
    $cek = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id = $id AND status = 'Menunggu Pengembalian'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE peminjaman SET status='Dikembalikan', tanggal_kembali = NOW() WHERE id = $id");
        $_SESSION['flash'] = "Pengembalian telah diverifikasi.";
    } else {
        $_SESSION['flash'] = "Data tidak valid atau sudah diverifikasi.";
    }
    header("Location: verifikasi_pengembalian.php");
    exit;
}

// PROSES TOLAK
if (isset($_GET['tolak'])) {
    $id = intval($_GET['tolak']);
    mysqli_query($conn, "UPDATE peminjaman SET status='Dipinjam' WHERE id = $id");
    $_SESSION['flash'] = "Pengembalian telah ditolak.";
    header("Location: verifikasi_pengembalian.php");
    exit;
}

// Ambil data
$query = mysqli_query($conn, "
    SELECT p.id, p.tanggal_pinjam, p.status,
           u.nama AS nama_user, d.nama_dosir
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id
    JOIN dosir d ON p.id_dosir = d.id
    WHERE p.status = 'Menunggu Pengembalian'
    ORDER BY p.tanggal_pinjam DESC
");

// Layout
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
    <br><br> <br> 
    <h5 class="fw-semibold mb-3">Verifikasi Pengembalian</h5>

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
                  <a href="?setujui=<?= $row['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui pengembalian ini?')">Setujui</a>
                  <a href="?tolak=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pengembalian ini?')">Tolak</a>
                </td>
              </tr>
              <?php endwhile; ?>
              <?php if (mysqli_num_rows($query) === 0): ?>
              <tr><td colspan="6" class="text-center">Tidak ada permintaan pengembalian.</td></tr>
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
