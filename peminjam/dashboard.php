<?php
require_once '../init.php';

// Cek apakah user sudah login dan merupakan peminjam
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'peminjam') {
  header("Location: ../index.php");
  exit;
}

require_once BASE_PATH . '/koneksi.php';
require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
require_once BASE_PATH . '/layouts/topbar.php';

$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['user_id'] ?? 0;

// Ambil data dosir total
$queryTotalDosir = mysqli_query($conn, "SELECT COUNT(*) AS total FROM dosir");
$totalDosir = mysqli_fetch_assoc($queryTotalDosir)['total'];

// Data peminjam (hanya data milik user login)
$queryDipinjam = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'Dipinjam' AND id_user = $user_id");
$totalDipinjam = mysqli_fetch_assoc($queryDipinjam)['total'];

$queryDikembalikan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status = 'Dikembalikan' AND id_user = $user_id");
$totalDikembalikan = mysqli_fetch_assoc($queryDikembalikan)['total'];

// Data peminjaman terbaru oleh user ini
$queryTerbaru = mysqli_query($conn, "
    SELECT p.*, d.nama_dosir 
    FROM peminjaman p 
    JOIN dosir d ON p.id_dosir = d.id 
    WHERE p.id_user = $user_id 
    ORDER BY p.tanggal_pinjam DESC 
    LIMIT 1
");

$dataTerbaru = mysqli_fetch_assoc($queryTerbaru);
?>

<!-- Konten utama -->
<div class="main-content py-4">
  <div class="container">
    <br><br>
    <h3 class="mb-4">Selamat datang, <?= $_SESSION['nama'] ?>!</h3>

    <div class="row g-4">
      <!-- Total Dosir -->
      <div class="col-md-3">
        <div class="card shadow">
          <div class="card-body text-center">
            <h5>Total Dosir</h5>
            <h3><?= $totalDosir ?></h3>
          </div>
        </div>
      </div>

      <!-- Dipinjam -->
      <div class="col-md-3">
        <div class="card shadow">
          <div class="card-body text-center">
            <h5>Dipinjam</h5>
            <h3 class="text-warning"><?= $totalDipinjam ?></h3>
          </div>
        </div>
      </div>

      <!-- Dikembalikan -->
      <div class="col-md-3">
        <div class="card shadow">
          <div class="card-body text-center">
            <h5>Dikembalikan</h5>
            <h3 class="text-success"><?= $totalDikembalikan ?></h3>
          </div>
        </div>
      </div>

      <!-- Peminjaman Terbaru -->
      <div class="col-md-3">
        <div class="card shadow">
          <div class="card-body">
            <h5>Peminjaman Terbaru</h5>
            <?php if ($dataTerbaru): ?>
              <p><strong>Dosir:</strong> <?= $dataTerbaru['nama_dosir'] ?></p>
              <p><strong>Status:</strong> <?= $dataTerbaru['status'] ?></p>
              <p><strong>Tanggal:</strong> <?= date('d M Y', strtotime($dataTerbaru['tanggal_pinjam'])) ?></p>
            <?php else: ?>
              <p>Belum ada peminjaman.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- penutup .container -->
</div>

</div>
<?php include BASE_PATH . '/layouts/footer.php'; ?>

