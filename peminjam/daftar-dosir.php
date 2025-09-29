<?php
require_once '../init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'peminjam') {
  header("Location: ../index.php");
  exit;
}

require_once BASE_PATH . '/koneksi.php';
require_once BASE_PATH . '/layouts/header.php';
require_once BASE_PATH . '/layouts/sidebar.php';
require_once BASE_PATH . '/layouts/topbar.php';

$userId = $_SESSION['user_id'];

// Tangkap kata kunci pencarian (jika ada)
$keyword = $_GET['search'] ?? '';

// Query pencarian
if ($keyword !== '') {
  $keyword_escaped = mysqli_real_escape_string($conn, $keyword);
  $query = mysqli_query($conn, "
    SELECT d.*, 
           p.status AS status_peminjaman 
    FROM dosir d 
    LEFT JOIN (
      SELECT id_dosir, status FROM peminjaman 
      WHERE status = 'Dipinjam'
    ) p ON d.id = p.id_dosir
    WHERE d.id_user = '$userId'
      AND (d.nama_dosir LIKE '%$keyword_escaped%' 
           OR d.deskripsi LIKE '%$keyword_escaped%') 
    ORDER BY d.id DESC
  ");
} else {
  $query = mysqli_query($conn, "
    SELECT d.*, 
           p.status AS status_peminjaman 
    FROM dosir d 
    LEFT JOIN (
      SELECT id_dosir, status FROM peminjaman 
      WHERE status = 'Dipinjam'
    ) p ON d.id = p.id_dosir 
    WHERE d.id_user = '$userId'
    ORDER BY d.id DESC
  ");
}


?>

<!-- Konten utama -->
<div class="main-content py-4">
  <div class="container">
    <br><br>
    <h4 class="mb-4">Daftar Dosir Tersedia</h4>

    <!-- Form Pencarian -->
    <form method="GET" class="mb-3">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Cari dosir..." value="<?= htmlspecialchars($keyword) ?>">
        <button class="btn btn-primary" type="submit">Cari</button>
        <?php if ($keyword): ?>
          <a href="daftar-dosir.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
      </div>
    </form>

    <!-- Tabel Dosir -->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-nowrap mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Dosir</th>
                    <th>Deskripsi</th>
                    <th>File Dosir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['nama_dosir']) ?></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td>
                      <?php if (!empty($row['file_pdf'])): ?>
                        <a href="javascript:void(0);" 
                          class="btn btn-sm btn-info view-pdf" 
                          data-pdf="<?= BASE_URL ?>/admin/view-pdf.php?file=<?= urlencode($row['file_pdf']) ?>">
                          Lihat File
                        </a>



                      <?php else: ?>
                        <em>Tidak ada</em>
                      <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($row['status_peminjaman'] === 'Dipinjam'): ?>
                        <span class="badge bg-warning">Sedang dipinjam</span>
                        <?php else: ?>
                        <span class="badge bg-success">Tersedia</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status_peminjaman'] === 'Dipinjam'): ?>
                        <button class="btn btn-sm btn-secondary" disabled>Ajukan</button>
                        <?php else: ?>
                        <a href="ajukan-peminjaman.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Ajukan</a>
                        <?php endif; ?>
                    </td>
                    </tr>
                <?php endwhile; ?>

          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- Modal untuk menampilkan PDF -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pdfModalLabel">Pratinjau File Dosir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" style="height: 80vh;">
        <iframe id="pdfFrame" src="" width="100%" height="100%" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

</div>

</div>
<?php include BASE_PATH . '/layouts/footer.php'; ?>


