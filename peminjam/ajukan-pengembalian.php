<?php
require_once '../init.php';
require_once '../koneksi.php';

// Cek apakah user adalah peminjam
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'peminjam') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Proses ajukan pengembalian
if (isset($_GET['ajukan'])) {
    $id = (int)$_GET['ajukan'];

    // Cek apakah dosir ini benar milik user dan status masih 'Dipinjam'
    $cek = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id = $id AND id_user = $user_id AND status = 'Dipinjam'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE peminjaman SET status = 'Menunggu Pengembalian' WHERE id = $id");
        $_SESSION['flash'] = "Pengajuan pengembalian berhasil.";
    } else {
        $_SESSION['flash'] = "Data tidak valid atau tidak bisa diajukan.";
    }

    header("Location: ajukan-pengembalian.php");
    exit;
}

// Ambil data dosir yang sedang dipinjam
$query = mysqli_query($conn, "
    SELECT p.id, p.tanggal_pinjam, p.status, d.nama_dosir, d.deskripsi, d.file_pdf
    FROM peminjaman p
    JOIN dosir d ON p.id_dosir = d.id
    WHERE p.id_user = $user_id AND (p.status = 'Dipinjam' OR p.status = 'Menunggu Pengembalian')
    ORDER BY p.tanggal_pinjam DESC
");


require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
require_once BASE_PATH . '/layouts/topbar.php';
?>

<div class="main-content py-4">
    <div class="container">
        <h4 class="mb-4">Ajukan Pengembalian Dosir</h4>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-info"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dosir</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Status</th>
                                    <th>Download</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama_dosir']) ?></td>
                                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span></td>
                                        <td>
                                            <?php if (!empty($row['file_pdf']) && $row['status'] === 'Dipinjam'): ?>
                                                <a href="<?= BASE_URL ?>/admin/view-pdf.php?file=<?= urlencode($row['file_pdf']) ?>" target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            <?php elseif ($row['status'] === 'Menunggu Pengembalian'): ?>
                                                <span class="text-muted">Tunggu verifikasi</span>
                                            <?php else: ?>
                                                <span class="text-muted">Tidak tersedia</span>
                                            <?php endif; ?>

                                        </td>
                                        <td>
                                            <?php if ($row['status'] === 'Dipinjam'): ?>
                                                <a href="?ajukan=<?= $row['id'] ?>" class="btn btn-sm btn-primary" onclick="return confirm('Ajukan pengembalian dosir ini?')">Ajukan</a>
                                            <?php elseif ($row['status'] === 'Menunggu Pengembalian'): ?>
                                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>

                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Tidak ada dosir yang sedang dipinjam.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div>
<?php include BASE_PATH . '/layouts/footer.php'; ?>

