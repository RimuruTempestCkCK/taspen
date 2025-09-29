<?php
ob_start();
require_once '../init.php';
require_once '../koneksi.php';

// Cek apakah pengguna sudah login dan memiliki peran admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';

$query = mysqli_query($conn, "
  SELECT p.*, u.nama AS nama_user, d.nama_dosir 
  FROM peminjaman p
  JOIN users u ON p.id_user = u.id
  JOIN dosir d ON p.id_dosir = d.id
  ORDER BY p.tanggal_pinjam DESC
");
?>

<?php include BASE_PATH . '/layouts/topbar.php'; ?>

<!-- Konten utama -->
<div class="main-content py-4">
    <div class="container">
        <br><br><br>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-semibold mb-1">Data Peminjaman</h5>
                <p class="text-muted mb-0">Riwayat seluruh peminjaman dosir</p>
            </div>
        </div>

        <!-- Tabel Peminjaman -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle text-nowrap mb-0">
                        <thead class="text-dark fs-4">
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
                                    <td><?= $row['tanggal_kembali'] ? date('d-m-Y', strtotime($row['tanggal_kembali'])) : '-' ?></td>
                                    <td>
                                        <span class="badge bg-<?= $row['status'] === 'Dipinjam' ? 'warning text-dark' : 'success' ?>">
                                            <?= $row['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($query) === 0): ?>
                                <tr><td colspan="6" class="text-center">Belum ada data peminjaman.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div> <!-- /.container -->
</div>

</div>
<?php include BASE_PATH . '/layouts/footer.php'; ?>


<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/sidebarmenu.js"></script>
<script src="../assets/js/app.min.js"></script>

<?php ob_end_flush(); ?>
