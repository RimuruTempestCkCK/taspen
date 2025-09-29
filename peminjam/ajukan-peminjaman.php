<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'peminjam') {
  header("Location: ../index.php");
  exit;
}

require_once BASE_PATH . '/koneksi.php';

// Ambil ID dosir dari URL
$id_dosir = $_GET['id'] ?? null;

if (!$id_dosir) {
  echo "ID dosir tidak valid.";
  exit;
}

// Cek apakah dosir valid
$query = mysqli_query($conn, "SELECT * FROM dosir WHERE id = '$id_dosir'");
$dosir = mysqli_fetch_assoc($query);

if (!$dosir) {
  echo "Dosir tidak ditemukan.";
  exit;
}

// Proses pengajuan peminjaman
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id_user = $_SESSION['user_id'];
  $tanggal_pinjam = date('Y-m-d');

  // Cek apakah dosir sudah dipinjam atau sedang menunggu persetujuan
  $cek = mysqli_query($conn, "
    SELECT * FROM peminjaman 
    WHERE id_dosir = '$id_dosir' 
    AND status IN ('Dipinjam', 'Menunggu Persetujuan')
  ");

  if (mysqli_num_rows($cek) > 0) {
    $error = "Dosir sedang dipinjam atau menunggu persetujuan.";
  } else {
    // Ajukan peminjaman dengan status Menunggu Persetujuan
    $insert = mysqli_query($conn, "
      INSERT INTO peminjaman (id_user, id_dosir, tanggal_pinjam, status) 
      VALUES ('$id_user', '$id_dosir', '$tanggal_pinjam', 'Menunggu Persetujuan')
    ");

    if ($insert) {
      $_SESSION['flash'] = "Peminjaman dosir berhasil diajukan. Menunggu persetujuan admin.";
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "Gagal mengajukan peminjaman.";
    }
  }
}

require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
require_once BASE_PATH . '/layouts/topbar.php';
?>

<!-- Konten utama -->
<div class="main-content py-4">
  <div class="container">
    <h4 class="mb-4">Ajukan Peminjaman Dosir</h4>

    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <h5><?= htmlspecialchars($dosir['nama_dosir']) ?></h5>
        <p><?= htmlspecialchars($dosir['deskripsi']) ?></p>
        <p><strong>Waktu dibuat:</strong> <?= date('d-m-Y H:i', strtotime($dosir['created_at'])) ?></p>

        <form method="POST">
          <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin meminjam dosir ini?')">
            Ajukan Peminjaman
          </button>
          <a href="daftar-dosir.php" class="btn btn-secondary">Kembali</a>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include BASE_PATH . '/layouts/footer.php'; ?>
</div>
